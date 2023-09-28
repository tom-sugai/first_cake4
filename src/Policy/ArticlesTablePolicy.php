<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Table\ArticlesTable;
use Authorization\IdentityInterface;

/**
 * Articles policy
 */
class ArticlesTablePolicy
{
    public function scopeIndex($user, $query)
    {
        return $query->where(['Articles.user_id' => $user->id]);
    }

    public function canIndex(IdentityInterface $identity)
    {
    // now did'nt use by Articles/index
    // here you can resolve true or false depending of the identity required characteristics
        //$identity['can_index']=true;
        //return $identity['can_index'];
        //return true;
    }
}
