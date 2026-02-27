import { Box, HStack, Icon, Text, VStack } from "@chakra-ui/react"
import { LucideStar } from "lucide-react"
import type { DepoimentoComponentProps } from "../../types/components"

export const DepoimentoComponent = ({ component, handleComponentClick }: { component: DepoimentoComponentProps, handleComponentClick: any }) => {
   
    return (
        <Box
        w={'100%'}
            key={component?.id}
            p={4}
            bg={component?.corFundo}
            borderRadius="md"
            textAlign={component?.modoHorizontal ? 'left' : 'center'}
            onClick={() => handleComponentClick(component)}
            cursor="pointer"
        >
            <HStack spacing={4} justifyContent={component?.modoHorizontal ? 'flex-start' : 'center'}>

                {component?.modoHorizontal ? (
                    <>
                        <Box as="img" src={component?.photo || '/placeholder.svg'} alt="Foto" boxSize="60px" borderRadius="full" />
                        <VStack spacing={1} align={'flex-start'}>
                            <Text fontWeight="bold" color={component?.corTexto}>{component?.depoimento}</Text>
                            <HStack>
                                {Array.from({ length: component?.estrelas }).map((_, index) => (
                                    <Icon key={index} as={LucideStar} color="yellow.400" __css={{ fill: "yellow.400" }} />
                                ))}
                            </HStack>
                            <Text fontSize="sm" color={component?.corTexto}>{component?.nome}</Text>
                        </VStack>
                    </>

                ) : (
                    <VStack spacing={1} align={'center'}>
                        <Box as="img" src={component?.photo || '/placeholder.svg'} alt="Foto" boxSize="60px" borderRadius="full" />
                        <Text fontWeight="bold" color={component?.corTexto}>{component?.depoimento}</Text>
                        <HStack>
                            {Array.from({ length: component?.estrelas }).map((_, index) => (
                                <Icon key={index} as={LucideStar} color="yellow.400" __css={{ fill: "yellow.400" }} />
                            ))}
                        </HStack>
                        <Text fontSize="sm" color={component?.corTexto}>{component?.nome}</Text>
                    </VStack>

                )}
            </HStack>
        </Box>
    )
}