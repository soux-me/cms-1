<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace User\Model\Entity;

/**
 * Provides "isAccessible()" method to entities.
 *
 * Entity must have a relationship with "User.Roles" model.
 */
trait AccessibleEntityTrait
{

    /**
     * Whether this entity can be accessed by current user.
     *
     * @param array|null $roles Array list of roles to checks against to, or NULL to
     *  automatically use entity's "roles" property
     * @return bool False if user has no permissions to see this entity due to
     *  role restrictions, true otherwise
     */
    public function isAccessible($roles = null)
    {
        if ($roles === null) {
            $roles = $this->get('roles');
        }

        if (empty($roles)) {
            return true;
        }

        $entityRolesID = [];
        foreach ($roles as $role) {
            $entityRolesID[] = $role->get('id');
        }

        $intersect = array_intersect($entityRolesID, (array)user()->get('role_ids'));
        return !empty($intersect);
    }
}
