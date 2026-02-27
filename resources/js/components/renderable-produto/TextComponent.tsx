import { Text } from "@chakra-ui/react";
import type { TextComponentProps } from "../../types/components";

export const TextComponent = (props: any) => {
    const { component, handleComponentClick } = props;
    return (
        <Text
            color={component.color || '#000000'}
            fontSize={component.fontSize || '16px'}
            fontWeight={component.fontWeight || 'normal'}
            onClick={handleComponentClick}
        >
            {component.text}
        </Text>
    );
}