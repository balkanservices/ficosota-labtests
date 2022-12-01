<div>
    Рецептура "{{ $recipe->getName() }}" е променена.
    <br /><br />
    Можете да прегледате рецептурата <a href="{{ route('recipes.view', ['id' => $recipe->id, '_locale' => 'bg'], true) }}">тук</a>.
    <br /><br />
    Можете да прегледате промените по рецептурата <a href="{{ route('recipes.audit_all_revisions', ['id' => $recipe->id, '_locale' => 'bg']) }}">тук</a>.
</div>