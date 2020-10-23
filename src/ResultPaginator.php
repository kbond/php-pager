<?php

namespace Zenstruck\Porpaginas;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
trait ResultPaginator
{
    public function paginate(int $page = 1, int $limit = Pager::DEFAULT_LIMIT): Pager
    {
        return new Pager($this, $page, $limit);
    }
}
