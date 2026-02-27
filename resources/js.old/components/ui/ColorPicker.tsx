import { HStack, Input, Text } from "@chakra-ui/react";

export interface ColorPickerProps {
    title: string;
    value: string;
    onChange: (value: string) => void;
}

export const ColorPicker = ({title, value, onChange}: ColorPickerProps) => {

    return(
        <HStack
        w={'100%'}
        borderWidth={1}
        borderColor={'gray.300'}
        borderRadius={7}
        p={'6px'}
        pl={4}
        my={0}
        justifyContent={'space-between'}
        >
            <Text fontSize={12} color={'gray.400'} fontWeight={'bold'}>{title}</Text>
            <Input
            w={'25px'}
            h={'25px'}
            p={0}
            type="color"
            value={value}
            onChange={(e) => onChange(e.target.value)}
            ></Input>
        </HStack>
    );
}