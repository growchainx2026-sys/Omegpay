import {
  Box,
  Flex,
  Text,
  Checkbox,
  Image,
  Button,
  Badge,
  HStack,
  VStack,
  Icon,
} from '@chakra-ui/react'
import { CheckCircle, LucideShieldCheck } from 'lucide-react'
import useConfig from '../../stores/config'
import { OrderBump } from '@/types/orderbump';
import { Helper } from '@/helpers/helpers';

export default function CheckoutOffer({metodo, onSubmit, isSubmitting}:{ metodo: any, onSubmit?: () => void, isSubmitting?: boolean }) {
  const { setting, produto, checkout, vendedor, template, selectedOrderBumps, toggleOrderBump, getTotalBruto, getTotalPrice } = useConfig();

  const handleCheckout = () => {
    if (!checkout) return;

    // Dados que serão enviados para o backend
    const checkoutPayload = {
      name: produto?.name,
      price: produto?.price,
      image: produto?.image,
      bumps: selectedOrderBumps.map(orderBumpId => {
        const orderBump = produto?.bumps.find(ob => ob.id === orderBumpId);
        return orderBump ? {
          id: orderBump.id,
          name: orderBump.name,
          description: orderBump.description,
          sale_price: orderBump.sale_price
        } : null;
      }).filter(Boolean),
      template: checkout.template,
      total_price: getTotalPrice(),
      payment_method: metodo
    };

  
    // Aqui você faria a requisição para o backend
    // await api.post('/checkout', checkoutPayload);
  };

  return (
    <VStack spacing={6} align="stretch" my={8}>
      {/* ORDER BUMPS */}
      {produto?.bumps?.map((orderBump: OrderBump) => {
        const isSelected = (selectedOrderBumps ?? []).includes(`${orderBump?.id}`);
        return (
          <Box 
            key={orderBump.id}
            border="2px dashed #D1D5DB" 
            borderRadius="md" 
            bg={isSelected ? template.btn_payment_bg_color : "gray.50"} 
            p={4}
          >
            <HStack justify="space-between" mb={2}>
              <Text 
                fontWeight="bold" 
                color={isSelected ? "white" : "gray.700"}
              >
                SIM, EU ACEITO ESSA OFERTA ESPECIAL!
              </Text>
              <CheckCircle color={isSelected ? "white" : "gray"} size={18} />
            </HStack>

            <Flex justify="space-between" align="center" bg="white" p={4} borderRadius="md">
              <Box>
                <Text fontSize="sm" color="gray.500">
                  {orderBump.product_name}
                </Text>
                <Text fontSize="sm" color="gray.400">
                  {orderBump.product_description}
                </Text>
              </Box>
              <Box textAlign="right">
                <Text fontSize="xs" as="s" color="red.400">
                  R$ {orderBump.valor_de}
                </Text>
                <Badge colorScheme="green" ml={2}>
                  {Math.round(((orderBump.valor_de - orderBump.valor_por) / orderBump.valor_de) * 100)}% OFF
                </Badge>
                <Text fontWeight="bold" fontSize="md" color="green.600">
                  R$ {orderBump.valor_por}
                </Text>
              </Box>
            </Flex>

            <Checkbox 
              mt={3} 
              colorScheme="green"
              isChecked={isSelected}
              onChange={() => toggleOrderBump(`${orderBump.id}`)}
              color={isSelected ? "white" : "inherit"}
            >
              <Text color={isSelected ? "white" : "inherit"}>
                Adicionar Produto
              </Text>
            </Checkbox>
          </Box>
        );
      })}

      {/* RESUMO DO PEDIDO */}
      <Box>
        <Text fontWeight="bold" mb={2} color={template.text_primary}>
          Resumo do pedido
        </Text>

        <Flex
          align="center"
          justify="space-between"
          bg="gray.50"
          borderRadius="md"
          p={4}
        >
          <HStack spacing={3}>
            <Image
              src={Helper.storageUrl(produto?.image) || "https://cdn-icons-png.flaticon.com/512/4086/4086679.png"}
              boxSize="50px"
              alt="Produto"
            />
            <Text fontWeight="medium" color={template.text_primary}>
              {produto?.name || "Carregando..."}
            </Text>
          </HStack>
          <Text fontWeight="bold" color={template.text_secondary}>
            {produto ? `${Helper.formatPrice(produto.price)}` : "R$ 0,00"}
          </Text>
        </Flex>

        {/* Order Bumps Selecionados */}
        {selectedOrderBumps.length > 0 && (
          <VStack spacing={2} mt={2}>
            {selectedOrderBumps.map(orderBumpId => {
              const orderBump = produto?.bumps.find(ob => ob.id === orderBumpId);
              if (!orderBump) return null;
              return (
                <Flex key={orderBumpId} justify="space-between" w="100%">
                  <Text fontSize="sm" color={template.text_primary}>
                    + {orderBump.name}
                  </Text>
                  <Text fontSize="sm" color={template.text_primary}>
                    R$ {orderBump.price}
                  </Text>
                </Flex>
              );
            })}
          </VStack>
        )}

        <HStack justifyContent="space-between" mt={2}>
          <Text fontWeight="semibold" color={template.text_primary}>Total</Text>
          <Text fontWeight="semibold" color={template.text_primary}>
            {produto?.desconto && produto.desconto > 0 ? (
              <Text as='span' color={'red.400'} textDecor={'line-through'}>{Helper.formatPrice(getTotalBruto())}</Text>
            ) : (<></>)}{' '}{Helper.formatPrice(getTotalPrice())}
          </Text>
        </HStack>
      </Box>

      {/* BOTÃO PAGAR COM PIX */}
      <Button
      id='button-send-payment'
        colorScheme="green"
        size="lg"
        w="100%"
        border={'none'}
        color={template.btn_payment_text_color}
        bg={template.btn_payment_bg_color}
        type="submit"
        isLoading={isSubmitting}
        loadingText="Processando pedido..."
        onClick={onSubmit || handleCheckout}
        _hover={{
          bg: template.btn_payment_bg_color,
          color: template.btn_payment_text_color,
          border: 'none',
          boxShadow: 'lg',
          transform: 'translateY(-2px)'
        }}
      >
        Pagar com {metodo === 'cartao' ? 'Cartão' : metodo && metodo.charAt ? metodo.charAt(0).toUpperCase() + metodo.slice(1) : 'Cartão'}
      </Button>

      {/* RODAPÉ */}
      <VStack spacing={2} mt={4} fontSize="xs" color="gray.500" textAlign="center">
        <Image
          src={Helper.storageUrl(setting?.favicon_light) || "https://cdn-icons-png.flaticon.com/512/4086/4086679.png"}
          alt="Space"
          h="24px"
        />
        <Text color={template.text_primary}>
          {setting.software_name} está processando este pagamento para o vendedor {produto?.name_exibition}
        </Text>
        <Text color="green.500" fontWeight="medium">
          <Icon as={LucideShieldCheck} __css={{fill: 'green.500'}} color={'white'} boxSize={6} />{' '}Compra 100% segura
        </Text>
        <Text color={template.text_primary}>
          Este site é protegido pelo reCAPTCHA do Google
          <br />
          <strong>Política de privacidade</strong> e <strong>Termos de serviço</strong>
          <br />
          * Parcelamento com acréscimo
        </Text>
        <Text color={template.text_primary}>
          Ao continuar, você concorda com os <strong>Termos de Compra</strong>
        </Text>
      </VStack>
    </VStack>
  )
}
