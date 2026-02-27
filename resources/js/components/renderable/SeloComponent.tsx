import { HStack } from "@chakra-ui/react";
import { selos } from "../drawers/SeloDrawer";
import type { SeloComponentProps } from "../../types/components";


export const SeloComponent = ({ component, handleComponentClick }: { component: SeloComponentProps, handleComponentClick: any }) => {
    return (
        <HStack
            onClick={() => handleComponentClick(component)}
            w={'100%'}
            justifyContent={component?.align.includes("center") ? "center" : `${component?.align}`}
            borderRadius="md"
            p={4}
            bg="transparent"
        >
            {selos.map(s => {
                if (s.label === component.selo) {
                    let Selo = s.value;
                    return <Selo {...component as any} />
                }
            })}
        </HStack>
    )
}