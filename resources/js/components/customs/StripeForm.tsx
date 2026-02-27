import {
    Box, HStack, Text, VStack,
    FormControl, FormLabel, Select, FormErrorMessage,
    Input
} from '@chakra-ui/react';
import { CreditCard, ShieldCheck, TicketPercent } from 'lucide-react';
import { Field, useFormikContext } from 'formik';
import { CardElement, useStripe, useElements } from "@stripe/react-stripe-js";
import { useEffect, useRef, useState } from 'react';
import { useConfig } from '@/stores/config';
import CheckoutOffer from '@/components/ui/Footerpayment';
import { Helper } from '@/helpers/helpers';

export function StripeForm({ onSubmit, isSubmitting }: any) {
    const { template, produto, getTotalPrice, setStripe } = useConfig();
    const { values, setFieldValue } = useFormikContext<any>();
    const stripe = useStripe();
    const elements = useElements();

    const parcelasRef = useRef<any>({});
    const [parcelas, setParcelas] = useState<{ label: string, value: string }[]>([]);
    const [clientSecret, setClientSecret] = useState<string | null>(null);

    useEffect(() => {
        if (values.payment.metodo !== "cartao" || !stripe || !elements) return;

        fetch("/api/stripe/payment-intent", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ amount: getTotalPrice(), currency: "brl", produto_id: produto?.uuid }),
        })
            .then((res) => res.json())
            .then((data) => {

                const cardElement = elements.getElement(CardElement);

                let payload: any = {
                    payment_method: {
                        card: cardElement,
                    },
                };

                if (parcelas.length > 1) {
                    payload.payment_method_options = {
                        card: {
                            // @ts-ignore
                            installments: {
                                plan: parcelas[Number(values.payment.parcelas)]?.value,
                            },
                        },
                    };
                }

                setStripe?.({
                    clientSecret: data.clientSecret,
                    elements: elements as any,
                    stripe: stripe,
                    payload,
                });
                
                if (data.plans?.length) {
                    const options = data.plans.map((plan: any, idx: number) => ({
                        value: idx.toString(),
                        label: `${plan.count}x de ${Helper.formatPrice(plan.amount / 100)}`,
                        plan,
                    }));
                    setParcelas(options);
                } else {
                    setParcelas([{ value: "0", label: `À vista - ${Helper.formatPrice(getTotalPrice())}` }]);
                }
            });


    }, [values.payment.metodo, stripe, elements]);

    if (!stripe || !elements) {
        return (
            <VStack spacing={4} align="stretch">
                <Text fontSize="sm" color={template.text_secondary}>Carregando formulário de cartão...</Text>
            </VStack>
        );
    }

    return (

        <VStack spacing={4} align="stretch">
            {/* Nome no cartão */}
            <Field name="payment.nomeCartao">
                {({ field, meta }: any) => (
                    <FormControl isInvalid={meta.error && meta.touched}>
                        <FormLabel fontSize={12} color={template.text_secondary}>Nome no cartão</FormLabel>
                        <Input {...field} placeholder="Nome como está no cartão" />
                        <FormErrorMessage>{meta.error}</FormErrorMessage>
                    </FormControl>
                )}
            </Field>

            {/* CardElement do Stripe */}
            <FormControl>
                <FormLabel fontSize={12} color={template.text_secondary}>Dados do cartão</FormLabel>
                <Box border="1px solid #ccc" p={3} borderRadius="md">          
                    <CardElement id='card-element-stripe' options={{ style: { base: { fontSize: "16px" } } }} />
                </Box>
            </FormControl>

            {/* Parcelas */}
            <Field name="payment.parcelas">
                {({ field, meta }: any) => (
                    <FormControl isInvalid={meta.error && meta.touched}>
                        <FormLabel fontSize={12} color={template.text_secondary}>Parcelas</FormLabel>
                        <Select {...field} ref={parcelasRef}>
                            <option>-- Selecione --</option>
                            {parcelas.map((option) => (
                                <option key={option.value} value={option.value}>
                                    {option.label}
                                </option>
                            ))}
                        </Select>
                        <FormErrorMessage>{meta.error}</FormErrorMessage>
                    </FormControl>
                )}
            </Field>
        </VStack>

    );
}
