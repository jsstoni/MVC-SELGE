<?php
namespace src;

class Paginacion
{
    public $actNext;
    public $actRet;
    public $siguiente;
    public $atras;
    public $start;
    public $end;
    public $totalPage;
    public $pagina;

    public function __construct($pagina = 1, $totalPage, $limite)
    {
        /**
        * Paginate
        * esta clase construye la paginacion de resultados
        * para los botones siguientes y atras
        * @param pagina Define el id de la pagina el cual se encuentra por defecto 1
        * @param totalPage Define el total de paginas que existen
        * @param limit Define el limite de resultado para mostrar
        */
        $atras = 0;
        $siguiente = 0;
        $totalPaginas = ceil(($totalPage / $limite));
        if ($totalPaginas > 1) {
            if ($pagina != 1) {
                $atras = 1;
            }
            if ($pagina != $totalPaginas) {
                $siguiente = 1;
            }
        }
        $linkAtras = intval($pagina) - 1;
        $linkSiguiente = intval($pagina) + 1;

        /*crear limite */
        $limite = $totalPage > $limite ? $limite : $totalPage;
        if ($pagina == 0) {
            $pagina = 1;
        }
        $n = $pagina; //numero de pagina
        $T = $limite;
        $Fin = $T + ($n - 1) * $T;
        $I = $Fin - $T;
        if ($Fin > $totalPage) {
            $Fin = floor(($Fin - $totalPage));
            $Fin = ($T - $Fin) + $I;
        }

        $this->pagina = $pagina;
        // activa el siguiente link
        $this->actNext = $siguiente;
        // numero de id de la pagina siguiente
        $this->siguiente = $linkSiguiente;
        // activa el anterior link
        $this->actRet = $atras;
        // numero de id de la pagina atras
        $this->atras = $linkAtras;
        // inicio limite
        $this->start = $I;
        // final limite
        $this->end = $T;
        //total paginas
        $this->totalPage = $totalPaginas;
    }
}
