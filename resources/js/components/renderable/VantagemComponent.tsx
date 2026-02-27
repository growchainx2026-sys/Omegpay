import { Box, Grid, GridItem, Icon, Text, VStack } from "@chakra-ui/react";
import type { VantagemComponentProps } from "../../types/components";
import { icons } from "../drawers/VantagemDrawer";
import { useEffect } from "react";

export const VantagemComponent = ({ component, handleComponentClick }: { component: VantagemComponentProps, handleComponentClick: any }) => {

    return (
        <Box
            onClick={() => handleComponentClick(component)}
            border="1px solid"
            borderColor={component?.subtitle_color}
            borderRadius="md"
            p={4}
            bg="transparent"
            w={'100%'}
        >
            <Grid templateColumns="auto 1fr" gap={4} alignItems="center">
                <GridItem hidden={component?.mode === 'vertical'}>
                    <Icon
                        as={icons.find((item) => item?.label === component?.icon)?.value}
                        boxSize={10}
                        fontWeight={'bold'}
                        color={component?.subtitle_color}
                    />
                </GridItem>
                <GridItem>
                    {component?.mode == 'horizontal' ? (
                        <VStack spacing={0} align="flex-start">
                            <Text
                                fontWeight="bold"
                                color={component?.title_color}
                                fontSize="md"
                            >
                                {component.title}
                            </Text>
                            <Text color={component?.subtitle_color} fontSize="sm">
                                {component?.subtitle}
                            </Text>
                        </VStack>
                    ) : (
                        <VStack spacing={0} align="center">
                            <Icon
                                as={icons.find((item) => item?.label === component?.icon)?.value}
                                boxSize={10}
                                fontWeight={'bold'}
                                color={component?.subtitle_color}
                            />
                            <Text
                                fontWeight="bold"
                                color={component?.title_color}
                                fontSize="md"
                            >
                                {component?.title}
                            </Text>
                            <Text color={component?.subtitle_color} fontSize="sm">
                                {component?.subtitle}
                            </Text>
                        </VStack>
                    )}
                </GridItem>
            </Grid>
        </Box>
    );
} 