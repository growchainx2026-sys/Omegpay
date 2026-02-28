import { useState, useEffect } from "react"
import { Box, HStack, Icon, Text, VStack } from "@chakra-ui/react"
import { LucideStar } from "lucide-react"
import type { DepoimentoComponentProps } from "../../types/components"
import { Helper } from "@/helpers/helpers"

const PHOTO_PLACEHOLDER = '/placeholder.svg';

function getPhotoSrc(photo: string | undefined | null): string {
    if (!photo) return PHOTO_PLACEHOLDER;
    if (photo.startsWith('data:') || photo.startsWith('http') || photo.startsWith('/')) return photo;
    return Helper.storageUrl(photo) || PHOTO_PLACEHOLDER;
}

export const DepoimentoComponent = ({ component, handleComponentClick }: { component: DepoimentoComponentProps, handleComponentClick: any }) => {
    const photoSrc = getPhotoSrc(component?.photo);
    const [imgSrc, setImgSrc] = useState(photoSrc);
    useEffect(() => { setImgSrc(photoSrc); }, [photoSrc]);
    const onError = () => setImgSrc(PHOTO_PLACEHOLDER);
    return (
        <Box
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
                        <Box as="img" src={imgSrc} onError={onError} alt="Foto" boxSize="60px" borderRadius="full" />
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
                        <Box as="img" src={imgSrc} onError={onError} alt="Foto" boxSize="60px" borderRadius="full" />
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