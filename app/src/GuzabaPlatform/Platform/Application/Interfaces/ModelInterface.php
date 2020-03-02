<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Application\Interfaces;

interface ModelInterface
{
    public static function get_data_by(array $index, int $offset = 0, int $limit = 0, bool $use_like = FALSE, ?string $sort_by = NULL, bool $sort_desc = FALSE, ?int &$total_found_rows = NULL) : iterable ;

    public function get_model_object_name() : string ;
}