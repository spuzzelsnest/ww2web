<?php

namespace App\Console\Commands;

use App\Models\Footage;
use App\Models\Operation;
use Illuminate\Console\Command;

class CheckFootageDates extends Command
{
    protected $signature = 'footage:check-dates
                            {--operation= : Only check footage of this operation (name or id)}
                            {--only= : Only show one kind: missing, impossible, outside, operation}';

    protected $description = 'List footage with a missing or impossible date, or a date outside its operation';

    public function handle(): int
    {
        $operations = Operation::all()->keyBy('id');

        $query = Footage::query()->orderBy('name');
        if ($filter = $this->option('operation')) {
            $op = ctype_digit((string) $filter)
                ? $operations->get((int) $filter)
                : $operations->first(fn ($o) => strcasecmp($o->operation, $filter) === 0);
            if (! $op) {
                $this->error("No operation \"{$filter}\".");

                return self::FAILURE;
            }
            $query->where('operationId', $op->id);
        }

        $rows = [];
        foreach ($query->get() as $footage) {
            foreach ($this->problems($footage, $operations) as [$kind, $text]) {
                $rows[] = [$kind, $footage->id, $footage->name, $footage->place, (string) $footage->getRawOriginal('date'), $operations->get($footage->operationId)?->operation ?? '-', $text];
            }
        }

        if ($only = $this->option('only')) {
            $rows = array_values(array_filter($rows, fn ($r) => $r[0] === $only));
        }

        if (! $rows) {
            $this->info('No problems found.');

            return self::SUCCESS;
        }

        usort($rows, fn ($a, $b) => [$a[0], $a[2]] <=> [$b[0], $b[2]]);

        $this->table(['kind', 'id', 'name', 'place', 'date', 'operation', 'problem'], $rows);

        $counts = array_count_values(array_column($rows, 0));
        ksort($counts);
        $this->line(collect($counts)->map(fn ($n, $k) => "{$k}: {$n}")->implode(', '));
        $this->line('"outside" can be fine (e.g. a post-war photo of a wreck); "missing" and "impossible" need a date fix.');

        return self::SUCCESS;
    }

    /** @return array<int, array{0: string, 1: string}> */
    private function problems(Footage $footage, $operations): array
    {
        $raw = (string) $footage->getRawOriginal('date');
        $operation = $operations->get($footage->operationId);
        $problems = [];

        if (! $operation) {
            $problems[] = ['operation', 'operationId '.$footage->operationId.' does not exist'];
        }

        if (! preg_match('/^(\d{4})-(\d{2})-(\d{2})/', $raw, $m)) {
            return array_merge($problems, [['missing', 'no date']]);
        }

        [$year, $month, $day] = [(int) $m[1], (int) $m[2], (int) $m[3]];

        if ($year === 0) {
            return array_merge($problems, [['missing', 'no date (0000)']]);
        }
        if ($year < 1930 || $year > 1960) {
            return array_merge($problems, [['impossible', "year {$year}"]]);
        }
        if ($month > 12 || ($month && $day && ! checkdate($month, $day, $year))) {
            return array_merge($problems, [['impossible', 'no such day']]);
        }

        // An unknown month or day ("1944-12-00") is a period, not a day.
        $from = sprintf('%04d-%02d-%02d', $year, $month ?: 1, $day ?: 1);
        $to = $month
            ? ($day ? $from : date('Y-m-t', strtotime(sprintf('%04d-%02d-01', $year, $month))))
            : sprintf('%04d-12-31', $year);

        if ($operation && $operation->start_date) {
            $start = $operation->start_date->format('Y-m-d');
            $end = $operation->end_date?->format('Y-m-d');

            if ($to < $start) {
                $problems[] = ['outside', "before the operation (starts {$start})"];
            } elseif ($end && $from > $end) {
                $problems[] = ['outside', "after the operation (ended {$end})"];
            }
        }

        return $problems;
    }
}
