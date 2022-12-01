<?php

namespace App\Helpers;

use App\Recipe;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\SimpleType\TextAlignment;
use PhpOffice\PhpWord\SimpleType\JcTable;

class RecipeExportHelper {

    public static function createWordFile($locale, Recipe $recipe, $rdSpecialists, $optionTypes) {
        $phpWord = self::createFile($locale, $recipe, $rdSpecialists, $optionTypes);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

        $fileName = str_replace(" ", "_", $recipe->getName()) . '.docx';

        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . $fileName);
        header("Content-Type: application/docx");
        header("Content-Transfer-Encoding: binary");
        $objWriter->save('php://output');
    }

    public static function createPdfFile($locale, Recipe $recipe, $rdSpecialists, $optionTypes) {
        // Due to PHPWord not handling styles in PDF export, use libreoffice to convert the docx to pdf
        $phpWord = self::createFile($locale, $recipe, $rdSpecialists, $optionTypes);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

        $fileName = str_replace(" ", "_", $recipe->getName()) . '.pdf';
        $tmpName = bin2hex(openssl_random_pseudo_bytes(10));

        $objWriter->save('/tmp/' . $tmpName . '.docx');

        $cmd = "export HOME=/tmp && /usr/bin/libreoffice --headless --convert-to pdf --outdir '/tmp' '/tmp/" . $tmpName . ".docx' 2>&1" ;
        shell_exec($cmd);

        if (!is_file('/tmp/' . $tmpName . '.pdf')) {
            if (is_file('/tmp/' . $tmpName . '.docx')) {
                unlink('/tmp/' . $tmpName . '.docx');
            }
            throw new \Exception('Failed to generate PDF file.');
        }

        header("Content-Disposition: attachment; filename=\"" . $fileName . "\"");
        header("Content-Type: application/pdf");
        header("Content-Transfer-Encoding: binary");

        flush();

        readfile('/tmp/' . $tmpName . '.pdf');

        if (is_file('/tmp/' . $tmpName . '.docx')) {
            unlink('/tmp/' . $tmpName . '.docx');
        }

        if (is_file('/tmp/' . $tmpName . '.pdf')) {
            unlink('/tmp/' . $tmpName . '.pdf');
        }

        exit;
    }

    public static function createFile($locale, Recipe $recipe, $rdSpecialists, $optionTypes) {
        $headerImage = __DIR__ . '/../../resources/files/header.png';
        $footerImage = __DIR__ . '/../../resources/files/footer.png';
        $phpWord = new \PhpOffice\PhpWord\PhpWord();


        $phpWord->setDefaultFontName('DejaVu Sans');
        $section = $phpWord->addSection();

        $header = $section->addHeader();
        if (is_file($headerImage)) {
            $header->addImage($headerImage, [
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
                'height' => 30,
            ]);
            $header->addTextBreak(1);
        }

        $footer = $section->addFooter();
        if (is_file($footerImage)) {
            $footer->addImage($footerImage, [
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
                'height' => 21,
            ]);
        }

        if ($recipe->product) {
            $productName = $recipe->product->getName();
            $header->addText(htmlentities($productName), [
                'size' => 11,
            ], [
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            ]);
            $header->addTextBreak(1);
        }

        $infoTableStyleName = 'General Info';
        $infoTableStyle = [
            'borderSize' => 0,
            'borderColor' => 'FFFFFF',
            'cellMargin' => 80,
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            'cellSpacing' => 50,
        ];

        $phpWord->addTableStyle($infoTableStyleName, $infoTableStyle);
        $table = $section->addTable($infoTableStyleName);

        $table->addRow();
        $table->addCell(2250)->addText(htmlspecialchars(Lang::get('recipes.revision_number') . ':'), ['bold' => true]);
        $table->addCell(2250)->addText(htmlspecialchars($recipe->revision_number), [], ['align' => 'center']);
        $table->addCell(2250)->addText(htmlspecialchars(Lang::get('recipes.revision_date') . ':'), ['bold' => true]);
        $table->addCell(2250)->addText(htmlspecialchars($recipe->revision_date), [], ['align' => 'center']);


        $rdSpecialist = isset ($rdSpecialists[$recipe->rd_specialist_id]) ? $rdSpecialists[$recipe->rd_specialist_id] : '';

        $table->addRow();
        $table->addCell(2250)->addText(htmlspecialchars(Lang::get('recipes.rd_specialist') . ':'), ['bold' => true]);
        $table->addCell(2250)->addText(htmlspecialchars($rdSpecialist), [], ['align' => 'center']);
        $table->addCell(2250)->addText(htmlspecialchars(Lang::get('recipes.in_effect_from') . ':'), ['bold' => true]);
        $table->addCell(2250)->addText(htmlspecialchars($recipe->in_effect_from), [], ['align' => 'center']);


        $section->addTextBreak(2);

        $ingredientsTableFirstRowStyle = [
            'borderBottomSize' => 18,
            'borderBottomColor' => '0000FF',
            'bgColor' => '66BBFF',
        ];
        $ingredientsTableStyle = [
            'borderSize' => 6,
            'borderColor' => '006699',
            'cellMargin' => 5,
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            'cellSpacing' => 5,
            'unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT,
            'width' => 100
        ];

        $headerCellStyle = ['size' => 7.5];
        $alignLeft = ['alignment' => JcTable::START];
        $alignRight = ['alignment' => JcTable::END];
        $alignCenter = ['alignment' => JcTable::CENTER];

        $isFirstRevision = ($recipe->revision_number == 0);

        foreach ($recipe->ingredients as $ingredient) {


            $section->addText($ingredient->name, ['bold' => true]);

            $ingredientsTableStyleName = 'Ingredient Table ' . $ingredient->id;
            $phpWord->addTableStyle($ingredientsTableStyleName, $ingredientsTableStyle, $ingredientsTableFirstRowStyle);
            $ingredientsTable = $section->addTable($ingredientsTableStyleName);

            $colCount = 8;
            if ($ingredient->hasCutLength()) {
                $colCount++;
            }
            if ($ingredient->hasElasticsCountAndElongation()) {
                $colCount += 2;
            }

            $colWidth = 9000 / $colCount;

            $ingredientsTable->addRow();
            $ingredientsTable->addCell($colWidth - 300)->addText(htmlspecialchars(Lang::get('recipes.ingredient_options_short.type.header')), $headerCellStyle, $alignCenter);
            $ingredientsTable->addCell($colWidth - 600)->addText(htmlspecialchars(Lang::get('recipes.ingredient_options_short.priority')), $headerCellStyle, $alignCenter);
            $ingredientsTable->addCell($colWidth + 1000)->addText(htmlspecialchars(Lang::get('recipes.ingredient_short.trade_name')), $headerCellStyle, $alignLeft);
            $ingredientsTable->addCell($colWidth - 450)->addText(htmlspecialchars(Lang::get('recipes.ingredient_short.width')), $headerCellStyle, $alignCenter);
            if ($ingredient->hasCutLength()) {
                $ingredientsTable->addCell($colWidth)->addText(htmlspecialchars(Lang::get('recipes.ingredient_short.cut_length')), $headerCellStyle, $alignCenter);
            }
            $ingredientsTable->addCell($colWidth)->addText(htmlspecialchars(Lang::get('recipes.ingredient_short.supplier')), $headerCellStyle, $alignLeft);
            $ingredientsTable->addCell($colWidth - 400)->addText(htmlspecialchars(Lang::get('recipes.ingredient_short.metric_unit')), $headerCellStyle, $alignCenter);
            $ingredientsTable->addCell($colWidth)->addText(htmlspecialchars(Lang::get('recipes.ingredient_short.consumption_per_piece')), $headerCellStyle, $alignRight);
            if ($ingredient->hasElasticsCountAndElongation()) {
                $ingredientsTable->addCell($colWidth)->addText(htmlspecialchars(Lang::get('recipes.ingredient_short.elastics_count')), $headerCellStyle, $alignCenter);
                $ingredientsTable->addCell($colWidth)->addText(htmlspecialchars(Lang::get('recipes.ingredient_short.elongation')), $headerCellStyle, $alignCenter);
            }
            $ingredientsTable->addCell($colWidth + 750)->addText(htmlspecialchars(Lang::get('recipes.ingredient_short.comment')), $headerCellStyle, $alignLeft);


            $previousRecipeIngredient = $ingredient->getPreviousRevisionRecipeIngredient();
            if ($previousRecipeIngredient) {
                $previousRecipeIngredientOptions = $previousRecipeIngredient->options;
            } else {
                $previousRecipeIngredientOptions = false;
            }

            $options = DB::table('recipe_ingredient_options')->where('recipe_ingredient_id', '=', $ingredient->id)->get()->toArray();

            foreach($options as $index => $option) {
                $rowStyle = [];
                $ingredientCellStyle = ['size' => 9];

                if ($option->type == \App\RecipeIngredientOption::TYPE_MAIN) {
                    $ingredientCellStyle['bgColor'] = '9cf4b5';
                    $ingredientCellStyle['vMerge'] = 'restart';
                }

                if (!$isFirstRevision && self::hasOptionChanged((array)$option, $previousRecipeIngredientOptions)) {
                    $ingredientCellStyle['color'] = 'c66255';
                }

                $ingredientsTable->addRow(null, $rowStyle);
                $type = !empty($option->type) ? Lang::get('recipes.ingredient_options_short.type.' . $option->type) : '';
                $ingredientsTable->addCell($colWidth - 300)->addText(htmlspecialchars($type), $ingredientCellStyle, $alignCenter);
                $ingredientsTable->addCell($colWidth - 600)->addText(htmlspecialchars($option->priority), $ingredientCellStyle, $alignCenter);
                $ingredientsTable->addCell($colWidth + 1000)->addText(htmlspecialchars($option->name), $ingredientCellStyle, $alignLeft);
                $ingredientsTable->addCell($colWidth - 450)->addText(htmlspecialchars($option->width), $ingredientCellStyle, $alignCenter);
                if ($ingredient->hasCutLength()) {
                    $ingredientsTable->addCell($colWidth)->addText(htmlspecialchars($option->cut_length), $ingredientCellStyle, $alignCenter);
                }
                $ingredientsTable->addCell($colWidth)->addText(htmlspecialchars($option->supplier), $ingredientCellStyle, $alignLeft);
                $ingredientsTable->addCell($colWidth - 400)->addText(htmlspecialchars($option->metric_unit), $ingredientCellStyle, $alignCenter);
                $ingredientsTable->addCell($colWidth)->addText(htmlspecialchars($option->consumption_per_piece), $ingredientCellStyle, $alignRight);
                if ($ingredient->hasElasticsCountAndElongation()) {
                    $ingredientsTable->addCell($colWidth)->addText(htmlspecialchars($option->elastics_count), $ingredientCellStyle, $alignCenter);
                    $ingredientsTable->addCell($colWidth)->addText(htmlspecialchars($option->elongation), $ingredientCellStyle, $alignCenter);
                }
                $ingredientsTable->addCell($colWidth + 750)->addText(htmlspecialchars($option->comment), $ingredientCellStyle, $alignLeft);
            }

            $section->addTextBreak(1);
        }

        $section->addText(htmlspecialchars(Lang::get('recipes.comment') . ':'), ['bold' => true]);
        $section->addText(htmlspecialchars($recipe->comment));

        return $phpWord;
    }

    private static function hasOptionChanged($option, $previousOptions) {
        if (empty($previousOptions)) {
            return true;
        }
        $hasChanged = true;

        foreach ($previousOptions->toArray() as $previousOption) {
            $previousOptionArr = (array) $previousOption;

            $sameOption = true;
            foreach (\App\RecipeIngredientOption::getComparableFields() as $field) {
                if ($previousOptionArr[$field] !== $option[$field]) {
                    $sameOption = false;
                }
            }

            if ($sameOption) {
                $hasChanged = false;
                break;
            }
        }

        return $hasChanged;
    }

}
