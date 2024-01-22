<?php
namespace App\Services;
use App\Traits\ApiUrl;

class ProductService
{
    use ApiUrl;

    public function showProduct($param)
    {
        $url = $this->isOnTheList('show_product');
        $url .= $param;
        $result = $this->postRequestPublic($url);

        return $result;

    }
}

?>
