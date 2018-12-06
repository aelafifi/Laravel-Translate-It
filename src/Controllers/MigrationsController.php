<?php

namespace ElMag\TranslateIt\Controllers;

use Illuminate\Http\Request;

class MigrationsController
{
    public function index(Request $request)
    {
        $types = [
            'bigIncrements',
            'bigInteger',
            'binary',
            'boolean',
            'char',
            'date',
            'dateTime',
            'dateTimeTz',
            'decimal',
            'double',
            'enum',
            'float',
            'geometry',
            'geometryCollection',
            'increments',
            'integer',
            'ipAddress',
            'json',
            'jsonb',
            'lineString',
            'longText',
            'macAddress',
            'mediumIncrements',
            'mediumInteger',
            'mediumText',
            'morphs',
            'multiLineString',
            'multiPoint',
            'multiPolygon',
            'nullableMorphs',
            'nullableTimestamps',
            'point',
            'polygon',
            'smallIncrements',
            'smallInteger',
            'string',
            'text',
            'time',
            'timeTz',
            'timestamp',
            'timestampTz',
            'tinyIncrements',
            'tinyInteger',
            'unsignedBigInteger',
            'unsignedDecimal',
            'unsignedInteger',
            'unsignedMediumInteger',
            'unsignedSmallInteger',
            'unsignedTinyInteger',
            'uuid',
            'year',
        ];

        $flags = [
            'rememberToken',
            'softDeletes',
            'softDeletesTz',
            'timestamps',
            'timestampsTz',
        ];

        return view('translateit::index', compact('types', 'flags'));
    }

    protected function template2real($template, $word)
    {

        return str_replace([
            'StudlyPlural',
            'StudlySingular',
            'snake_plural',
            'snake_singular',
        ], [
            studly_case(str_plural($word)),
            studly_case(str_singular($word)),
            snake_case(str_plural($word)),
            snake_case(str_singular($word)),
        ], $template);
    }

    public function generate(Request $request)
    {
        $model_name = $request->get('model_name');

        $main_table_migration = $this->template2real(
            load_template('main_table_migration.php'),
            $model_name
        );

        $translations_table_migration = $this->template2real(
            load_template('translations_table_migration.php'),
            $model_name
        );

        $main_model = $this->template2real(
            load_template('main_model.php'),
            $model_name
        );

        $translations_model = $this->template2real(
            load_template('translations_model.php'),
            $model_name
        );

        $lines = [];

        foreach ($request->get('columns') as $column) {
            $arg0 = $column['arg0'] === null ? '' : ', ' . json_encode(real_value($column['arg0']));
            $arg1 = $column['arg1'] === null ? '' : ', ' . json_encode(real_value($column['arg1']));
            $default = $column['default'] === null ? '' : '->default(' . json_encode(real_value($column['arg1'])) . ')';
            $line = '$table->' . $column['typename'] . '(\'' . $column['column_name'] . '\'' . $arg0 . $arg1 . ')';
            $line .= $default;
            foreach ($column['flag'] ?? [] as $flag => $_) {
                $line .= '->' . $flag . '()';
            }
            $line .= ';';
            $lines[] = $line;
        }

        foreach (array_keys($request->get('flag')) as $flag) {
            $lines[] = '$table->' . $flag . '();';
        }

        $glue = "\n            ";
        $lines = implode($glue, $lines);

        if (!empty($lines)) {
            $main_table_migration = str_replace(
                '$table->increments(\'id\');',
                '$table->increments(\'id\');' . $glue . $lines,
                $main_table_migration
            );
        }

        $filename = date('Y_m_d_His') . '_create_' . snake_case(str_plural($model_name)) . '_table.php';
        $filepath = database_path('migrations/' . $filename);
        file_put_contents($filepath, $main_table_migration);

        $lines = [];

        foreach ($request->get('translated') as $column) {
            $arg0 = $column['arg0'] === null ? '' : ', ' . json_encode(real_value($column['arg0']));
            $arg1 = $column['arg1'] === null ? '' : ', ' . json_encode(real_value($column['arg1']));
            $default = $column['default'] === null ? '' : '->default(' . json_encode(real_value($column['arg1'])) . ')';
            $line = '$table->' . $column['typename'] . '(\'' . $column['column_name'] . '\'' . $arg0 . $arg1 . ')';
            $line .= $default;
            foreach ($column['flag'] ?? [] as $flag => $_) {
                $line .= '->' . $flag . '()';
            }
            $line .= ';';
            $lines[] = $line;
        }

        $glue = "\n            ";
        $lines = implode($glue, $lines);

        if (!empty($lines)) {
            $translations_table_migration = str_replace(
                '$table->string(\'locale\', 2)->index();',
                '$table->string(\'locale\', 2)->index();' . $glue . $lines,
                $translations_table_migration
            );
        }

        $filename = date('Y_m_d_') . (date('His') + 1) . '_create_' . snake_case(str_singular($model_name)) . '_translations_table.php';
        $filepath = database_path('migrations/' . $filename);
        file_put_contents($filepath, $translations_table_migration);

        $main_attributes = collect($request->get('columns'))->map(function ($item) {
            return '\'' . $item['column_name'] . '\'';
        })->implode(', ');

        $translated_attributes = collect($request->get('translated'))->map(function ($item) {
            return '\'' . $item['column_name'] . '\'';
        })->implode(', ');

        $main_model = str_replace([
            '/*F*/',
            '/*T*/',
        ], [
            $main_attributes ?: '\'id\'',
            $translated_attributes,
        ], $main_model);

        $translations_model = str_replace([
            '/*F*/',
        ], [
            $translated_attributes,
        ], $translations_model);

        $filepath = app_path(studly_case(str_singular($model_name)) . '.php');
        file_put_contents($filepath, $main_model);

        $filepath = app_path(studly_case(str_singular($model_name)) . 'Translation.php');
        file_put_contents($filepath, $translations_model);

        return redirect()->back();
    }
}
