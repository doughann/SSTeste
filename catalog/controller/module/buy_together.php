<?php
class ControllerModuleBuyTogether extends Controller {

    private $moduleOptions;

	protected function index($setting) {
		$this->language->load('module/buy_together');

    	$this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['lang'] = $this->language;

        $this->loadOptions();
        $this->data['displayOptions'] = $this->moduleOptions;

        if (isset($this->request->get['product_id'])) {
            $productId = (int)$this->request->get['product_id'];
        } else {
            $productId = 0;
        }

        $groupDiscounts = $this->config->get('group_discount');
        if(empty($groupDiscounts))
        {
            $groupDiscounts = array();
        }

        $currentDiscounts = array();
        foreach($groupDiscounts as $discount)
        {
            if(!isset($discount['productD'])){
                $discount['productD'] = 0;
            }
            if(!isset($discount['productAoption'])){
            	$discount['productAoption'] = array();
            }
            if(!isset($discount['productBoption'])){
            	$discount['productBoption'] = array();
            }
            if(!isset($discount['productCoption'])){
            	$discount['productCoption'] = array();
            }
            if(!isset($discount['productDoption'])){
            	$discount['productDoption'] = array();
            }
            
            $discount = $this->hideProduct($discount, $productId, 'productA');
            $discount = $this->hideProduct($discount, $productId, 'productB');
            $discount = $this->hideProduct($discount, $productId, 'productC');
            $discount = $this->hideProduct($discount, $productId, 'productD');
            
            if($discount['productA']==$productId
            ||$discount['productB']==$productId
            ||$discount['productC']==$productId
            ||$discount['productD']==$productId)
            {
                if($discount['productA']!=$productId){
                    if($discount['productB']==$productId){
                        $discount['productB'] = $discount['productA'];
                        $options = $discount['productBoption'];
                        $discount['productBoption'] = $discount['productAoption'];
                    }elseif($discount['productC']==$productId){
                        $discount['productC'] = $discount['productA'];
                        $options = $discount['productCoption'];
                        $discount['productCoption'] = $discount['productAoption'];
                    }elseif($discount['productD']==$productId){
                        $discount['productD'] = $discount['productA'];
                        $options = $discount['productDoption'];
                        $discount['productDoption'] = $discount['productAoption'];
                    }
                    $discount['productA'] = $productId;
                    $discount['productAoption'] = $options;
                }
                $currentDiscounts[] = $discount;
            }
        }
        
        $categoryDiscounts = $this->config->get('category_discount');
        if(empty($categoryDiscounts))
        {
            $categoryDiscounts = array();
        }
        $productCategories = $this->model_catalog_product->getCategories($productId);
        foreach($productCategories as $category){
            foreach($categoryDiscounts as $discount){
                if($discount['categoryId']==$category['category_id']){
                    $discount['productA'] = $productId;
                    unset($discount['categoryId']);
                    $currentDiscounts[] = $discount;
                }
            }
        }
        
        if(count($currentDiscounts)>0)
        {
            $this->load->model('tool/image');
            $this->load->model('catalog/product');
            $this->load->model('total/group_discount');

             foreach($currentDiscounts as $discount)
             {
                 $productA = $this->loadProduct($discount['productA']);
                 $productB = $this->loadProduct($discount['productB']);
                 $productC = $this->loadProduct($discount['productC']);
                 $productD = $this->loadProduct($discount['productD']);

                 $price = $this->model_total_group_discount->getPrice($discount['productA'], (isset($discount['productAoption'])?$discount['productAoption']:array()));
                 $price += $this->model_total_group_discount->getPrice($discount['productB'], (isset($discount['productBoption'])?$discount['productBoption']:array()));
                 $price += $this->model_total_group_discount->getPrice($discount['productC'], (isset($discount['productCoption'])?$discount['productCoption']:array()));
                 $price += $this->model_total_group_discount->getPrice($discount['productD'], (isset($discount['productDoption'])?$discount['productDoption']:array()));
                 
                 $productIds = array(
                    $productA['key'],
                    $productB['key']
                 );
                 if($productC)
                 {
                     $productIds[] = $productC['key'];
                 }
                 if($productD)
                 {
                     $productIds[] = $productD['key'];
                 }

                 $discountAmount = $this->getDiscount($discount, $price);
                 
                 $options = $this->loadDiscountProductOptions(array(), $discount, 'productA');
                 $options = $this->loadDiscountProductOptions($options, $discount, 'productB');
                 $options = $this->loadDiscountProductOptions($options, $discount, 'productC');
                 $options = $this->loadDiscountProductOptions($options, $discount, 'productD');

                 $this->data['discounts'][] = array(
                    'productA' => $productA,
                    'productB' => $productB,
                    'productC' => $productC,
                    'productD' => $productD,
                    'discountType' => $discount['discountType'],
                    'discount' => $discount['discount'],
                    'message' => empty($productC)? $this->language->get('buy_together_message_two'):(empty($productD)?$this->language->get('buy_together_message_three'):$this->language->get('buy_together_message_four')),
                    'button' => sprintf($this->language->get('button_cart'), empty($productC)? $this->language->get('two'):(empty($productD)?$this->language->get('three'):$this->language->get('four'))),
                    'price' => $this->formatPrice($price),
                    'discountedPrice' => $this->formatPrice($price-$discountAmount),
                    'productIds' => join(',', $productIds),
                    'discountAmount' => $this->formatPrice($discountAmount),
                 	'options' => json_encode($options)
                 );
             }


            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/buy_together.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/module/buy_together.tpl';
            } else {
                $this->template = 'default/template/module/buy_together.tpl';
            }

            $this->render();
        }
  	}
  	
  	private function loadDiscountProductOptions($options, $discount, $productType)
  	{
  		$productTypeOption = $productType . 'option';
  		if(isset($discount[$productTypeOption])){
  			$options[$discount[$productType]] = $discount[$productTypeOption];
  		}
  		return $options;
  	}

    private function loadOptions(){
        $this->moduleOptions = $this->config->get('buy_together_options');
        if(empty($this->moduleOptions)){
            $this->moduleOptions = array(
                'display' => 0,
                'titleWidth' => 17,
                'imageWidth' => $this->config->get('config_image_cart_width'),
                'imageHeight' => $this->config->get('config_image_cart_height')
            );
        }
    }

    private function getDiscount($discount, $price)
    {
        if($discount['discountType']=='percent')
        {
            $result = $price * ($discount['discount']/100);
        }
        else
        {
            $result = $discount['discount'];
        }
        return $result;
    }

    private function formatPrice($price)
    {
        return $this->currency->format($price);
    }
    
    private function shortenTitle($title){
        if($this->moduleOptions['display']==1&&$this->moduleOptions['titleWidth']>0){
            return mb_substr($title, 0, $this->moduleOptions['titleWidth'], 'UTF-8') . '...';
        }
        return $title;
    }

    private function loadProduct($productId)
    {
        if($productId>0)
        {
            $productData = $this->model_catalog_product->getProduct($productId);

            if ($productData['image']) {
                $image = $this->model_tool_image->resize($productData['image'], $this->moduleOptions['imageWidth'], $this->moduleOptions['imageHeight']);
            } else {
                $image = '';
            }

            if ((float)$productData['special']) {
                $price = $this->tax->calculate($productData['special'], $productData['tax_class_id'], $this->config->get('config_tax'));
            }
            else{
                $price = $this->tax->calculate($productData['price'], $productData['tax_class_id'], $this->config->get('config_tax'));
            }

            $product = array(
                'key'      => $productData['product_id'],
                'thumb'    => $image,
                'name'     => $this->shortenTitle($productData['name']),
                'model'    => $productData['model'],
                'price'    => $price,
                'href'     => $this->url->link('product/product', 'product_id=' . $productData['product_id'])
            );
        }
        else
        {
            $product = false;
        }
        return $product;
    }
    
    function hideProduct($discount, $productId, $productType)
    {
    	if(isset($discount[$productType . 'hide'])&&$discount[$productType . 'hide']==1&&$productId==$discount[$productType]){
    		$discount[$productType] = -1;
    	}
    	return $discount;
    }
}
