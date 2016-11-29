<?php

namespace Assely\Singularity\Model;

use Assely\Contracts\Singularity\PreservesMetaInterface;
use Assely\Singularity\Model;
use Assely\Singularity\PreservesMeta;

class TermModel extends Model implements PreservesMetaInterface
{
    use PreservesMeta;

    /**
     * Default term params.
     *
     * @var array
     */
    protected $defaults = [
        'title' => [],
        'description' => '',
        'preserve' => 'multiple',
    ];

    /**
     * Read term meta from Singularity.
     *
     * @param  array  $parameters
     *
     * @return array
     */
    public function readMeta(array $parameters)
    {
        return call_user_func_array('get_term_meta', $parameters);
    }

    /**
     * Save term meta to Singularity.
     *
     * @param  array  $parameters
     *
     * @return bool|int
     */
    public function makeMeta(array $parameters)
    {
        return call_user_func_array('add_term_meta', $parameters);
    }

    /**
     * Save term meta to Singularity.
     *
     * @param  array  $parameters
     *
     * @return bool|int
     */
    public function saveMeta(array $parameters)
    {
        return call_user_func_array('update_term_meta', $parameters);
    }

    /**
     * Remove term meta form Singularity,.
     *
     * @param  array  $parameters
     *
     * @return bool
     */
    public function removeMeta(array $parameters)
    {
        return call_user_func_array('delete_term_meta', $parameters);
    }
}
