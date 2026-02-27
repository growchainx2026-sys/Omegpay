import { Box, Heading, HStack } from "@chakra-ui/react"
import type { HeaderComponentProps } from "../../types/components"
import useConfig from "@/stores/config"

export const HeaderComponent = ({ component, handleComponentClick }: { component: HeaderComponentProps, handleComponentClick: any }) => {
    
   
    return (
        <HStack
            w={'100%'}
            gap={3}
            justifyContent={'space-between'}
            px={component.padding}
        >
            {component.align === 'left' ? (
                <>
                    <Box
                        key={component.id}
                        as="img"
                        src={component.image || '/product-placeholder.svg'}
                        alt="Imagem"
                        w={{base: '50px', xl: "233px"}}
                        h="auto"
                        cursor="pointer"
                        onClick={() => handleComponentClick(component)}
                        _hover={{ opacity: 0.8 }}
                        transition="opacity 0.2s"
                    />
                    <Heading fontSize={{base: '16px', xl: component.fontSize}} color={component.color}>{component.title}</Heading>
                </>
            ) : (
                <>
                    <Heading fontSize={component.fontSize} color={component.color}>{component.title}</Heading>
                    <Box
                        key={component.id}
                        as="img"
                        src={component.image || '/product-placeholder.svg'}
                        alt="Imagem"
                        maxW={{base: '50px', xl: "233px"}}
                        w={'100%'}
                        h="auto"
                        cursor="pointer"
                        onClick={() => handleComponentClick(component)}
                        _hover={{ opacity: 0.8 }}
                        transition="opacity 0.2s"
                    />
                </>
            )}

        </HStack>
    )
}