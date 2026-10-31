<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class NumberImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public $names;
    public $emails;
    public $numbers;

    public function collection(Collection $rows)
    {
        $this->names = [];
        $this->emails = [];
        $this->numbers = [];

        // Loop through the rows and add the numbers to the array
        foreach ($rows as $key => $row) {
            if ($key == 0) {
                continue;
            }

            $this->names[] = $row[1] ?? '';
            $this->emails[] = $row[2] ?? '';
            $this->numbers[] = $row[0] ?? '';
        }
    }
}
