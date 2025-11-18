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
        // Usamos la constante URL_ROOT (definida en configuracion.php)
        // Para evitar problemas con globals o mayúsculas/minúsculas
        if (!defined('URL_ROOT')) {
            // fallback: intentar global $RUTAVISTA si por alguna razón no está definida
            global $RUTAVISTA;
            $base = isset($RUTAVISTA) ? rtrim($RUTAVISTA, '/') . '/' : '/';
        } else {
            $base = rtrim(URL_ROOT, '/') . '/';
        }

        // Asegurarse que la carpeta 'vista' esté incluida exactamente como la querés
        // Si URL_ROOT ya incluye '.../PWD_TPFINAL/' entonces esto quedará correcto
        // Construimos la ruta final: base + 'Vista/' + archivo.php
        $menu = $this->getMenus($id);
        $archivo = $menu->getMenombre() . '.php';

        // Normalizamos el path para evitar dobles slashes
        return $base . 'vista/' . ltrim($archivo, '/');
    }
}
