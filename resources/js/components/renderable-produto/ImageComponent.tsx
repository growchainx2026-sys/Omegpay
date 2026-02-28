import { useState, useEffect } from "react";
import { Box, HStack } from "@chakra-ui/react";
import type { ImageComponentProps } from "../../types/components";
import { Helper } from "@/helpers/helpers";

const PLACEHOLDER = '/product-placeholder.svg';

export const ImageComponent = (props: any) => {
    const { component, handleComponentClick } = props;
    const rawSrc = component?.src || component?.url;
    const isStoragePath = rawSrc && typeof rawSrc === 'string' && !rawSrc.startsWith('http') && !rawSrc.startsWith('/');
    const resolvedSrc = isStoragePath ? (Helper.storageUrl(rawSrc) || PLACEHOLDER) : (rawSrc || PLACEHOLDER);
    const [imgSrc, setImgSrc] = useState(resolvedSrc);
    useEffect(() => { setImgSrc(resolvedSrc); }, [resolvedSrc]);
    return (
        <a onClick={component?.redirectUrl?.includes?.('http') ? () => window.open(component?.redirectUrl, '_blank') : () => { }}>
            <HStack
                w={'100%'}
                display={'flex'}
                justifyContent={component?.align}
                onClick={handleComponentClick}
            >
                <Box
                    as="img"
                    src={imgSrc}
                    onError={() => setImgSrc(PLACEHOLDER)}
                    alt={component?.alt || 'Imagem'}
                    w={`${component?.size || 150}px`} // Use a propriedade `size` de `component`
                    h={'auto'}
                    objectFit={'contain'}
                    cursor="pointer"
                    _hover={{ opacity: 0.8 }}
                    transition="opacity 0.2s"
                />
            </HStack>
        </a>
    );
}