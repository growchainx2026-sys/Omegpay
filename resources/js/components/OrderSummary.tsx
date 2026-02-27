import {
  VStack,
  HStack,
  Text,
  Divider,
  Button,
  Image,
  Box,
  Skeleton,
  SkeletonText,
} from '@chakra-ui/react'
import { useConfig } from '../stores/config'
import { Helper } from '@/helpers/helpers'

export function OrderSummary() {
  const { checkout, produto, isLoadingCheckout } = useConfig();

  // Formatação de preço em Real brasileiro
  const formatPrice = (price: number) => {
    return new Intl.NumberFormat('pt-BR', {
      style: 'currency',
      currency: 'BRL'
    }).format(price);
  };

  if (isLoadingCheckout) {
    return (
      <VStack spacing={4} align="stretch">
        <HStack spacing={4}>
          <Skeleton boxSize="60px" borderRadius="md" />
          <Box flex={1}>
            <SkeletonText noOfLines={1} spacing="4" skeletonHeight="4" />
            <SkeletonText noOfLines={1} spacing="4" skeletonHeight="3" mt={2} />
          </Box>
        </HStack>
        <Divider />
        <VStack spacing={2} align="stretch">
          <SkeletonText noOfLines={2} spacing="4" skeletonHeight="4" />
        </VStack>
        <Skeleton height="40px" borderRadius="md" />
      </VStack>
    );
  }

  if (!checkout) {
    return (
      <VStack spacing={4} align="stretch">
        <Text color="gray.500" textAlign="center">
          Erro ao carregar dados do produto
        </Text>
      </VStack>
    );
  }
  
  return (
    <VStack spacing={4} align="stretch" p={{ base: 4, lg: undefined }} mb={-2}>
      <HStack spacing={4}>
        <Image
          src={Helper.storageUrl(produto?.image) || "/product-placeholder.svg"}
          alt={produto?.name}
          boxSize="60px"
          objectFit="cover"
          borderRadius="md"
        />
        <Box flex={1}>
          <Text fontWeight="bold">{produto?.name}</Text>
          <Text color="gray.500" fontSize="sm">
            1 X de {formatPrice(produto?.price as number)}
          </Text>
        </Box>
      </HStack>

      <VStack spacing={2} align="stretch">
        <HStack alignItems={'flex-start'} fontSize={'12px'} mb={2}>
          <Text>
            {produto?.description}
          </Text>
        </HStack>
        <HStack justify="space-between">
          <Text>Total</Text>
          <Text fontWeight="bold">
            {formatPrice(produto?.price as number)}
          </Text>
        </HStack>
      </VStack>
    </VStack>
  )
}