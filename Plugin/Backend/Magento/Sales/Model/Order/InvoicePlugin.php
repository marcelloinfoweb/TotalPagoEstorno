<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Funarbe\TotalPagoEstorno\Plugin\Backend\Magento\Sales\Model\Order;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Invoice;
use Magento\Setup\Exception;

class InvoicePlugin
{
    protected OrderRepositoryInterface $orderRepository;

    /**
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository
    ) {
        $this->orderRepository = $orderRepository;
    }


//    /**
//     * @param \Magento\Sales\Model\Order\Invoice $subject
//     * @param $result
//     * @throws \Exception
//     */
//    public function afterRegister(Invoice $subject, $result): void
//    {
//        $order_id = $result->getOrderId();
//        $order = $this->orderRepository->get($order_id);
//        $statusHistoryItem = $order->getStatusHistoryCollection()->getLastItem();
//        //$status = $statusHistoryItem->getStatusLabel();
//        $comment = $statusHistoryItem->getComment();
//
//        if (!empty($comment) && strpos($comment, 'Authorized amount of') !== false) {
//
//            preg_match_all('/R\$(.*?)\./', $comment, $matches);
//            $price = str_replace(' ', '', $matches[1][0]);
//            $valor = (double)str_replace(',', '.', $price);
//
//
//            /** @var \Magento\Sales\Model\Order\Invoice $invoice */
//            foreach ($order->getInvoiceCollection()->getItems() as $invoice) {
//                var_dump($invoice);
//                die('dentro');
//                $invoice->setSubtotal($valor);
//                $invoice->setBaseSubtotal($valor);
//
//
//                $this->attribute->saveAttribute($invoice, true);
//
//            }
//
//            die('Fora');

//            $result->setBaseAdjustmentNegative($order->getBaseSubtotal());
//            $result->setAdjustmentNegative($order->getSubtotal());
//            $result->setSubtotal($valor);
//            $result->setBaseSubtotal($valor);

//            $order->setBaseAdjustmentNegative($order->getBaseSubtotal());
//            $order->setAdjustmentNegative($order->getSubtotal());
//            $order->setSubtotal($valor);
//            $order->setBaseSubtotal($valor);
//            $order->save();

//        }
//
//    }
}
