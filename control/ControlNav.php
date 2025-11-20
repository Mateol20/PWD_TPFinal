<?php
class ControlNav
{
    /**
     * Devuelve un menu segun el id
     * @param mixed $id
     * @return mixed
     */
    public function getMenus($id)
    {
        $abmMenu = new ABMMenu();

        $menu = $abmMenu->buscar(["idmenu" => $id, "medesahabilitado" => null])[0];
        return $menu;
    }

    /**
     * Devuelve un arreglo de submenus segun el id
     * @param mixed $id
     * @return array
     */
    public function getSubMenus($id)
    {
        $subMenus = [];
        $abmMenu = new ABMMenu();
        $menus = $abmMenu->buscar(["idpadre" => $id, "medeshabilitado" => null]);
        foreach ($menus as $menu) {
            if ($menu->getMedeshabilitado() === null) {
                $subMenus[] = $menu;
            }
        }
        return $subMenus;
    }


    public function getUrl($id)
    {

        if (!defined('URL_ROOT')) {

            global $RUTAVISTA;
            $base = isset($RUTAVISTA) ? rtrim($RUTAVISTA, '/') . '/' : '/';
        } else {
            $base = rtrim(URL_ROOT, '/') . '/';
        }

        $menu = $this->getMenus($id);
        $archivo = $menu->getMenombre() . '.php';

        return $base . 'vista/' . ltrim($archivo, '/');
    }
}
