<?php
///**
// * Copyright © Marcelo Caetano All rights reserved.
// * See COPYING.txt for license details.
// */
//declare(strict_types=1);
//
//namespace Funarbe\TotalPagoEstorno\Plugin\Backend\Magento\Sales\Model\Order;
//
//use Magento\Sales\Api\OrderRepositoryInterface;
//
//class Creditmemo
//{
////
////    /**
////     * @var \Magento\Sales\Api\OrderRepositoryInterface
////     */
////    private OrderRepositoryInterface $orderRepository;
////
////    /**
////     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
////     */
////    public function __construct(OrderRepositoryInterface $orderRepository)
////    {
////        $this->orderRepository = $orderRepository;
////    }
////
////    /**
////     * @param \Magento\Sales\Model\Order\Creditmemo $subject
////     * @return array
////     * @throws \Exception
////     */
////    public function beforeGetAdjustmentNegative(
////        \Magento\Sales\Model\Order\Creditmemo $subject
////    ): array {
////        $order = $this->getCurrentOrderId($subject->getOrderId());
////
////        $order->setAdjustmentNegative($subject->getSubtotal());
////        $order->save();
////
////        $subject->setAdjustmentNegative($subject->getSubtotal());
////
////        return [];
////    }
////
////    /**
////     * @param \Magento\Sales\Model\Order\Creditmemo $subject
////     * @return array
////     * @throws \Exception
////     */
////    public function beforeGetBaseAdjustmentNegative(
////        \Magento\Sales\Model\Order\Creditmemo $subject
////    ): array {
////        $order = $this->getCurrentOrderId($subject->getOrderId());
////
////        $order->setBaseAdjustmentNegative($subject->getBaseSubtotal());
////        $order->save();
////
////        $subject->setBaseAdjustmentNegative($subject->getBaseSubtotal());
////
////        return [];
////    }
////
////    public function getCurrentOrderId($order_id)
////    {
////        return $this->orderRepository->get($order_id);
////    }
//}
