import {
  VStack,
  Heading,
  Button,
  HStack,
  Image,
  Text,
  Box,
  Icon,
  Skeleton,
  SkeletonText,
} from '@chakra-ui/react'
import { LucideCreditCard, LucideQrCode, LucideFileText } from 'lucide-react'
import useConfig from '../stores/config'

export function PaymentOptions() {
  const { checkoutData, isLoadingCheckout } = useConfig();
  
  // Mapeamento de métodos de pagamento para ícones e labels
  const paymentMethodsConfig = {
    pix: {
      icon: LucideQrCode,
      label: 'PIX',
      color: 'teal'
    },
    boleto: {
      icon: LucideFileText,
      label: 'Boleto',
      color: 'orange'
    },
    cartao: {
      icon: LucideCreditCard,
      label: 'Cartão',
      color: 'blue'
    }
  };
  
  if (isLoadingCheckout) {
    return (
      <VStack spacing={4} align="stretch">
        <SkeletonText noOfLines={1} spacing="4" skeletonHeight="6" />
        <HStack spacing={4}>
          <Skeleton height="80px" flex={1} borderRadius="md" />
          <Skeleton height="80px" flex={1} borderRadius="md" />
          <Skeleton height="80px" flex={1} borderRadius="md" />
        </HStack>
      </VStack>
    );
  }
  
  if (!checkoutData) {
    return (
      <VStack spacing={4} align="stretch">
        <Heading size="md">Métodos de Pagamento</Heading>
        <Text>Nenhum método de pagamento disponível</Text>
      </VStack>
    );
  }

  return (
    <VStack spacing={4} align="stretch">
      <Heading size="md">Pagamento</Heading>

      <HStack spacing={4}>
        {['pix', 'boleto', 'cartao'].map((method) => {
          const config = paymentMethodsConfig[method];
          if (!config) return null;
          
          return (
            <Button
              key={method}
              variant="outline"
              p={6}
              flex={1}
              display="flex"
              flexDirection="column"
              alignItems="center"
              height="auto"
              colorScheme={config.color}
              _hover={{
                bg: `${config.color}.50`,
                borderColor: `${config.color}.300`
              }}
            >
              <Box mb={2}>
                <Icon as={config.icon} boxSize={6} />
              </Box>
              <Text>{config.label}</Text>
            </Button>
          );
        })}
      </HStack>
    </VStack>
  )
}