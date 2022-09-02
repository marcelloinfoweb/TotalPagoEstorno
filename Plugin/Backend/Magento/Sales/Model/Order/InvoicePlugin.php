<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Funarbe\TotalPagoEstorno\Plugin\Backend\Magento\Sales\Model\Order;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Invoice;

class InvoicePlugin
{
    protected OrderRepositoryInterface $orderRepository;

    /**
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     */
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @throws NoSuchEntityException
     * @throws InputException
     * @throws Exception
     */
    public function afterRegister(Invoice $subject, $result)
    {
        $order_id = $result->getOrderId();
        $order = $this->orderRepository->get($order_id);
        $statusHistoryItem = $order->getStatusHistoryCollection()->getLastItem();
        //$status = $statusHistoryItem->getStatusLabel();
        $comment = $statusHistoryItem->getComment();

        if (!empty($comment) && strpos($comment, 'Authorized amount of') !== false) {
            $resultFinal = [];
            preg_match_all('/R\$(.*?)\./', $comment, $matches);
            $price = str_replace(' ', '', $matches[1][0]);
            $valor = (double)str_replace(',', '.', $price);

            $resultFinal->setBaseAdjustmentNegative($order->getBaseSubtotal());
            $resultFinal->setAdjustmentNegative($order->getSubtotal());
            $resultFinal->setSubtotal($valor);
            $resultFinal->setBaseSubtotal($valor);

            $order->setBaseAdjustmentNegative($order->getBaseSubtotal());
            $order->setAdjustmentNegative($order->getSubtotal());
            $order->setSubtotal($valor);
            $order->setBaseSubtotal($valor);
            $order->save();
        }
        return $resultFinal;
    }
}
