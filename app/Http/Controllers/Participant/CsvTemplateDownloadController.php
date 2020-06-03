<?php

namespace BristolSU\Module\DataEntry\Http\Controllers\Participant;

use BristolSU\Module\DataEntry\Http\Controllers\Controller;
use Illuminate\Http\Response;
use League\Csv\Writer;

class CsvTemplateDownloadController extends Controller
{

    public function download()
    {
        $header = [];
        $schemas = settings('column_types', []);
        foreach($schemas as $rowId => $schema) {
            if(array_key_exists('visible', $schema) && $schema['visible']) {
                $header[] = $schema['header'];
            }
        }
        
        $csv = Writer::createFromString();
        $csv->insertOne($header);
        
        return new Response($csv->getContent(), 200, [
            'Content-Encoding' => 'none',
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template.csv"',
            'Content-Description' => 'File Transfer',
            'X-Vapor-Base64-Encode' => 'True',
        ]);
    }
    
}