<?php
declare(strict_types=1);

namespace ConsultMee\Models;

use ConsultMee\Core\Model;

class Category extends Model
{
    public function listWithConsultants(): array
    {
        return $this->fetchAll(
            "SELECT DISTINCT c.id, c.category_name AS name, c.img
             FROM categories c
             INNER JOIN consultants con
               ON con.area_of_expertise COLLATE utf8mb4_general_ci = c.category_name
             ORDER BY c.category_name ASC"
        );
    }
}
