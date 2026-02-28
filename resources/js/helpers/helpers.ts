import useConfig from "@/stores/config";
import { Produto } from "@/types/produto";
import axios from "axios";
import dayjs from "dayjs";

type PaymentMethod = "pix" | "credit_card" | "boleto";
type Status = "waiting_payment" | "paid" | "refunded";

  const formatPrice = (price: number) => {
    return new Intl.NumberFormat('pt-BR', {
      style: 'currency',
      currency: 'BRL'
    }).format(price);
  };


  const gerarUTM = async (
  method: PaymentMethod,
  status: Status,
  transaction: any,
  produto: Produto,
  apiToken: string,
  ip: string,
  params?: {
    src: string | null,
    sck: string | null,
    utm_source: string | null,
    utm_campaign: string | null,
    utm_medium: string | null,
    utm_content: string | null,
    utm_term: string | null,
  }
) => {
  const dataAtual = dayjs().format("YYYY-MM-DD HH:mm:ss");
  const { setting }= useConfig();

  const { src, sck, utm_source, utm_campaign, utm_medium, utm_content, utm_term } = params || {
    src: null,
    sck: null,
    utm_source: null,
    utm_campaign: null,
    utm_medium: null,
    utm_content: null,
    utm_term: null,
  };
  try {
    await axios.post(
      "https://api.utmify.com.br/api-credentials/orders",
      {
        orderId: transaction.idTransaction,
        platform: setting?.software_name || "spacepag",
        paymentMethod: method,
        status: status,
        createdAt: dataAtual,
        approvedDate: status === "paid" ? dataAtual : null,
        refundedAt: status === "refunded" ? dataAtual : null,
        customer: {
          name: transaction.client_name,
          email: transaction.client_email,
          phone: transaction.client_telefone,
          document: transaction.client_document,
          country: "BR",
          ip: ip,
        },
        products: [
          {
            id: produto.uuid,
            name: produto.description,
            planId: produto.uuid,
            planName: produto.name,
            quantity: 1,
            priceInCents: transaction.amount * 100,
          },
        ],
        trackingParameters: {
          src,
          sck,
          utm_source,
          utm_campaign,
          utm_medium,
          utm_content,
          utm_term,
        },
        commission: {
          totalPriceInCents: transaction.amount * 100,
          gatewayFeeInCents: 0,
          userCommissionInCents: 0,
        },
        isTest: false,
      },
      {
        headers: {
          "x-api-token": apiToken,
        },
      }
    );

  } catch (error) {
    console.error("Erro ao enviar UTM:", error);
  }
}

  /**
   * Monta URL para arquivos em storage. Evita /storage//path e /storage/undefined.
   * Preserva data: URLs e URLs absolutas (http/https). Retorna undefined para box_default inexistente.
   * @param path Caminho relativo (com ou sem barra no início), data URL ou URL absoluta
   * @returns URL completa ou undefined se path inválido
   */
  const storageUrl = (path: string | undefined | null): string | undefined => {
    if (path == null || path === "" || String(path) === "undefined") return undefined;
    const s = String(path);
    // Preserva data: URLs e URLs absolutas (não passar por /storage/)
    if (s.startsWith("data:") || s.startsWith("http://") || s.startsWith("https://") || s.startsWith("/")) {
      return s;
    }
    // box_default.png/svg pode não existir no storage — evita 404
    if (s.includes("box_default")) return undefined;
    const trimmed = s.replace(/^\/+/, "");
    return trimmed ? `/storage/${trimmed}` : undefined;
  };

  export const Helper = {
    formatPrice,
    gerarUTM,
    storageUrl,
  };