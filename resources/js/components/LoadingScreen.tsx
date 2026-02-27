import { Box, Flex, Spinner, Text } from '@chakra-ui/react';
import { keyframes } from '@emotion/react';

// Animação de brilho/reflexo
const shimmer = keyframes`
  0% {
    background-position: -200px 0;
  }
  100% {
    background-position: calc(200px + 100%) 0;
  }
`;

// Animação de rotação suave
const rotate = keyframes`
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
`;

interface LoadingScreenProps {
  message?: string;
}

export const LoadingScreen = ({ message = 'Carregando checkout...' }: LoadingScreenProps) => {
  return (
    <Flex
      position="fixed"
      top={0}
      left={0}
      right={0}
      bottom={0}
      bg="rgba(0, 0, 0, 0.8)"
      backdropFilter="blur(10px)"
      zIndex={9999}
      align="center"
      justify="center"
      direction="column"
    >
      <Spinner color="white" />
      {/* Pontos animados */}
      <Text
        color="white"
        fontSize="lg"
        mt={2}
        css={{
          '&::after': {
            content: '""',
            animation: 'dots 1.5s steps(4, end) infinite'
          },
          '@keyframes dots': {
            '0%, 20%': { content: '""' },
            '40%': { content: '"."' },
            '60%': { content: '".."' },
            '80%, 100%': { content: '"..."' }
          }
        }}
      >
        Carregando
      </Text>
    </Flex>
  );
};