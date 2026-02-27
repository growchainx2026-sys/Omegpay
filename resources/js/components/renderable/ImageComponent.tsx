import { Box, HStack } from "@chakra-ui/react";
import type { ImageComponentProps } from "../../types/components";

export const ImageComponent = (props: any) => {
    const { component, handleComponentClick } = props;
    return (
        <HStack
            w={'100%'}
            display={'flex'}
            justifyContent={component?.align}
            onClick={handleComponentClick}
        >
            <Box
                as="img"
                src={component?.url || '/product-placeholder.svg'}
                alt={component?.alt || 'Imagem'}
                w={`${component?.size || 150}px`} // Use a propriedade `size` de `component`
                h={'auto'}
                objectFit={'contain'}
                cursor="pointer"
                _hover={{ opacity: 0.8 }}
                transition="opacity 0.2s"
            />
        </HStack>
    );
}