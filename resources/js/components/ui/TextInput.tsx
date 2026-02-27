import { Box, HStack, Input, Text, type InputProps } from "@chakra-ui/react";
import useConfig from "../../stores/config";

export interface TextInputProps extends Partial<InputProps> {
    title: string;
    value: string;
    onChange: (value: string) => void;
    
}

export const TextInput = ({title, value, onChange, ...rest}: TextInputProps) => {

     const { config } = useConfig();

    return(
        <HStack
        w={'100%'}
        pos={'relative'}
        >
            <Box
            pos={'absolute'} top={-2} px={1} bg={config.bg_color} left={2} zIndex={2}
            >
            <Text fontSize={12} color={'gray.200'} fontWeight={'bold'} bg={'transparent'}>{title}</Text>
            </Box>
            <Input
            px={2}
            type={rest.type || "text"}
            value={value}
            onChange={(e) => onChange(e.target.value)}
            {...rest}
            ></Input>
        </HStack>
    );
}