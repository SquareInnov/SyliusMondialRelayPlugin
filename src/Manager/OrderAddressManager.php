<?php

namespace Sherlockode\SyliusMondialRelayPlugin\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Sherlockode\SyliusMondialRelayPlugin\MondialRelay\Client as MondialRelayClient;
use Sylius\Component\Core\Factory\AddressFactoryInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Class OrderAddressManager
 */
class OrderAddressManager
{
    /**
     * @var AddressFactoryInterface
     */
    private $addressFactory;

    /**
     * @var MondialRelayClient
     */
    private $apiClient;

    /**
     * @var PointAddressManager
     */
    private $addressFormater;

    private $em;

    /**
     * @param AddressFactoryInterface $addressFactory
     * @param MondialRelayClient      $apiClient
     * @param PointAddressManager     $addressFormater
     * @param EntityManagerInterface  $em
     */
    public function __construct(
        AddressFactoryInterface $addressFactory,
        MondialRelayClient $apiClient,
        PointAddressManager $addressFormater,
        EntityManagerInterface $em
    ) {
        $this->addressFactory = $addressFactory;
        $this->apiClient = $apiClient;
        $this->addressFormater = $addressFormater;
        $this->em = $em;
    }

    /**
     * @param GenericEvent $event
     */
    public function updateShippingAddress(GenericEvent $event): void
    {
        $order = $event->getSubject();

        foreach ($order->getShipments() as $shipment) {
            if (!$shipment->getPickupPointId()) {
                continue;
            }

            $this->setShippingAddress($order, $shipment->getPickupPointId());
        }
    }

    /**
     * @param OrderInterface $order
     * @param string         $pickupPointId
     */
    private function setShippingAddress(OrderInterface $order, string $pickupPointId): void
    {
        try {
            $pickupPoint = $this->apiClient->getPickupPoint(
                $pickupPointId,
                $order->getShippingAddress()->getCountryCode()
            );
        } catch (\Exception $e) {
            return;
        }

        if (!$pickupPoint) {
            return;
        }

        $shippingAddress = $order->getShippingAddress();

        /** @var AddressInterface $address */
        $address = $this->addressFactory->createNew();
        $address->setStreet(sprintf(
            '%s, %s',
            $this->addressFormater->getPointLabel($pickupPoint),
            $this->addressFormater->getPointShortAddress($pickupPoint)
        ));
        $address->setFirstName($shippingAddress->getFirstName());
        $address->setLastName($shippingAddress->getLastName());
        $address->setPhoneNumber($shippingAddress->getPhoneNumber());
        $address->setCity($pickupPoint->city());
        $address->setPostcode($pickupPoint->cp());
        $address->setCountryCode($pickupPoint->country());
        $order->setShippingAddress($address);

        $this->em->flush();
    }
}
