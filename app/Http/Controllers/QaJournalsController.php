<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Recipe;
use Illuminate\Support\Facades\DB;
use App\QaJournal;
use App\Http\Requests\QaJournalRequest;

class QaJournalsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$qaJournals = QaJournal::where('deleted', 0)
            ->orderBy('id', 'desc')
            ->paginate(self::PAGE_SIZE);

        return view('qa-journals/index', [
			'qaJournals' => $qaJournals,
		]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function edit($locale, $id)
    {
		$qaJournal = QaJournal::find($id);

        if ($qaJournal->deleted) {
            return redirect()->route('qa_journals.index');
        }

        $recipes = Recipe::getNamesArray();

        return view('qa-journals/edit', [
			'qaJournal' => $qaJournal,
            'recipes' => $recipes,
		]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function newRecord(Request $request, $locale)
    {
        $recipeId = $request->query->get('recipeId');

        if(empty($recipeId)) {
            $recipes = Recipe::getNamesArray();

            return view('qa-journals/choose-recipe', [
                'recipes' => $recipes,
            ]);
        }

        $qaJournal = new QaJournal([
            'recipe_id' => $recipeId,
        ]);


        $qaJournal->save();

        $qaJournal->initOptions();

        return redirect()->route('qa_journals.edit', ['id' => $qaJournal->id]);
    }

    public function process(QaJournalRequest $request, $locale, $id)
    {
        $qaJournal = QaJournal::find($id);

        if(empty($qaJournal)) {
            $qaJournal = new QaJournal($request->all());
            $qaJournal->save();
        } else {
            $qaJournal->update($request->all());
            $qaJournal->updateSelectedOptions($request->all()['ingredients'], $request->all()['option_batch_number'], $request->all()['option_fs_batch_number']);
        }

        if(!empty($request->get('save_and_create_samples_list'))) {
            return redirect()->route('samples_lists.new', ['qaJournalId' => $qaJournal->id]);
        }

        $request->session()->flash('status', __('qa_journals.saved'));
        return redirect()->route('qa_journals.edit', ['id' => $qaJournal->id]);
    }

    public function delete(QaJournalRequest $request, $locale, $id)
    {
        $qaJournal = QaJournal::findOrFail($id);

        if (!$qaJournal->samplesLists->isEmpty()) {
            return redirect()->route('qa_journals.index');
        }

        $qaJournal->deleted = 1;
        $qaJournal->save();

        $request->session()->flash('status', __('qa_journals.deleted'));

        return redirect()->route('qa_journals.index');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function audit($locale, $id)
    {
		$qaJournal = QaJournal::findOrFail($id);

        $auditLog = $qaJournal->audits()->with('user')->orderBy('id', 'DESC')->get();

        $ingredients = $qaJournal->ingredients()->get();

        foreach($ingredients as $ingredient) {
            $auditLogTmp = $ingredient->audits()->with('user')->orderBy('id', 'DESC')->get();
            $auditLog = $auditLog->merge($auditLogTmp);
        }

        $auditLog = $auditLog->sortByDesc(function ($item, $key) {
            return $item->created_at;
        });

        return view('qa-journals/audit', [
			'qaJournal' => $qaJournal,
            'auditLog' => $auditLog,
		]);
    }
}
