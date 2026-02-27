import {
  Box,
  Grid,
  GridItem,
  Skeleton,
  SkeletonText,
  VStack,
  HStack,
} from '@chakra-ui/react';

export const SkeletonMain = () => {
  return (
    <Grid
      templateColumns="1fr 400px"
      gap={8}
      minH="100vh"
      p={8}
    >
      {/* Área principal com skeleton */}
      <GridItem>
        <VStack spacing={6} align="stretch">
          {/* Skeleton do produto */}
          <Box p={6} borderRadius="lg" bg="white" boxShadow="sm">
            <HStack spacing={4} align="flex-start">
              <Skeleton boxSize="60px" borderRadius="lg" />
              <Box flex={1}>
                <Skeleton height="20px" width="60%" mb={2} />
                <Skeleton height="16px" width="40%" mb={1} />
                <Skeleton height="14px" width="50%" />
              </Box>
            </HStack>
          </Box>

          {/* Skeleton dos componentes */}
          {[1, 2, 3, 4].map((item) => (
            <Box key={item} p={6} borderRadius="lg" bg="white" boxShadow="sm">
              <SkeletonText noOfLines={3} spacing={4} skeletonHeight={4} />
              <HStack spacing={4} mt={4}>
                <Skeleton height="40px" width="100px" />
                <Skeleton height="40px" width="100px" />
                <Skeleton height="40px" width="100px" />
              </HStack>
            </Box>
          ))}
        </VStack>
      </GridItem>

      {/* Sidebar com skeleton */}
      <GridItem>
        <Box position="sticky" top={8}>
          <Box bg="white" p={6} borderRadius="lg" boxShadow="sm">
            {/* Header do resumo */}
            <Box
              h="45px"
              borderTopRadius="lg"
              mb={6}
              mx={-6}
              mt={-6}
            >
              <Skeleton height="45px" borderTopRadius="lg" />
            </Box>

            {/* Produto no resumo */}
            <HStack spacing={4} mb={6} align="flex-start">
              <Skeleton boxSize="60px" borderRadius="lg" />
              <Box flex={1}>
                <Skeleton height="18px" width="70%" mb={2} />
                <Skeleton height="14px" width="50%" />
              </Box>
            </HStack>

            {/* Total */}
            <Box py={6} borderY="1px" borderColor="gray.200">
              <Skeleton height="16px" width="30%" mb={2} />
              <Skeleton height="20px" width="50%" mb={1} />
              <Skeleton height="14px" width="40%" />
              <Skeleton height="14px" width="35%" mt={2} />
            </Box>

            {/* Botões de ação */}
            <VStack spacing={3} mt={6}>
              <Skeleton height="48px" width="100%" />
              <Skeleton height="40px" width="100%" />
            </VStack>
          </Box>
        </Box>
      </GridItem>
    </Grid>
  );
};