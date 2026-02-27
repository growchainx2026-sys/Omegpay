import { Icon, ListItem, OrderedList, Text, VStack } from "@chakra-ui/react"
import { LucideCheckCircle2 } from "lucide-react"
import type { ListaComponentProps } from "../../types/components"

export const ListaComponent = ({ component, handleComponentClick }: { component: ListaComponentProps, handleComponentClick: any }) => {
    return (
        <VStack
            onClick={() => handleComponentClick(component)}
            w={'100%'}
            alignItems={component.alinhamento === 'left' ? 'flex-start' : component.alinhamento === 'center' ? 'center' : 'flex-end'}
            justifyContent={'flex-start'}
        >
            {component.temTitulo && (
                <Text px={component.alinhamento !== 'right' ? 4 : 3} textAlign={component.alinhamento} fontSize={`${component.tamanho}px`} color={component.corTexto} fontWeight="bold">
                    {component.titulo}
                </Text>
            )}
            <OrderedList>
                {component.items?.map((item: any, index: any) => (
                    <ListItem
                        key={index}
                        color={component.corTexto}
                        fontSize={`${component.tamanho}px`}
                        textAlign={component.alinhamento}
                        display="flex"
                        alignItems="center"
                    >
                        {component.icone === 'check' && (
                            <Icon
                                as={LucideCheckCircle2}
                                color={component.corIcone}
                                mr={2}
                            />
                        )}
                        {component.icone === 'nenhum' && (<></>)}
                        {component.icone === 'decimal' && (
                            <Text mr={2}>{index + 1}.</Text>
                        )}
                        {component.icone === 'circulo' && (
                            <Text mr={2}>•</Text>
                        )}

                        {item}
                    </ListItem>
                ))}
            </OrderedList>
        </VStack>
    )
}