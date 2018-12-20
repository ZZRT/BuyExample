<?php

namespace Wise\BuyExample\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;

class SetPrice implements ObserverInterface
{
    protected $request;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $isExampleAction = (bool)$this->getParam('is_example');

        $product = $observer->getEvent()->getData('product');
        $isExampleProductEnabled = (bool)$product->getData('wise_sample_product_enabled');
        $item = $observer->getEvent()->getData('quote_item');
        $itemQty = $product->getQty();
        $isExampleProduct = (bool)$item->getData('is_wise_product_example');
        if ($isExampleAction && $isExampleProductEnabled) {
            $price = $this->getExampleProductPrice($product); //set your price here
            $item = ($item->getParentItem() ? $item->getParentItem() : $item);
            $this->changeItemData($item,$price);
        } elseif($isExampleProduct && !$isExampleAction) {
            $price = $product->getPrice();
            $item = ($item->getParentItem() ? $item->getParentItem() : $item);
            $this->changeItemData($item,$price, $itemQty, 0);
        }
    }

    /**
     * Get value by key
     * @param string $key
     * @return string
     */
    public function getParam($key)
    {
        return $this->request->getParam($key);
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return float|int
     */
    public function getExampleProductPrice($product)
    {
        if ((bool)$product->getData('wise_sample_product_price_type')) {
            return $product->getData('wise_sample_product_price_type');
        } else {
            $price = (float)$product->getPrice() * (float)$product->getData('wise_sample_price_value') / 100;
            return $price;
        }
    }

    protected function changeItemData($item, $price, $qty = 0, $isExample = 1)
    {
        $item = ($item->getParentItem() ? $item->getParentItem() : $item);
        $item->setQty($qty);
        $item->setCustomPrice($price);
        $item->setOriginalCustomPrice($price);
        $item->setData('is_wise_product_example', $isExample);
        $item->getProduct()->setIsSuperMode(true);
    }
}