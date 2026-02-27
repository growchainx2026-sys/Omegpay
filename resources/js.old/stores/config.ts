import { create } from "zustand";
import { createJSONStorage, persist } from "zustand/middleware";
import type { PersistOptions } from "zustand/middleware/persist";
import type { StateCreator } from "zustand";
import { Template } from "@/types/template";
import { Setting } from "@/types/setting";
import { Produto } from "@/types/produto";
import { Checkout } from "@/types/checkout";
import { Stripe, StripeElement } from "@stripe/stripe-js";

export interface Config {
  bg_color: string;
  card_bg_color: string;
  button_bg_color: string;
  border_color: string;
  text_color: string;
}



let template = {
  theme: "custom" as "light" | "dark" | "custom",
  font: "Roboto" as 'Roboto' | 'Open Sans',
  text_primary: "black",
  text_secondary: "gray",
  text_active: "#0b6856",
  icon_color: "#000000",
  bg: "#f1f1f1",
  bg_form_payment: "#ffffff",

  btn_unselected_text_color: "gray",
  btn_unselected_bg_color: "#f1f1f1",
  btn_unselected_icon_color: "#000000",

  btn_selected_text_color: "#ffffff",
  btn_selected_bg_color: "#0b6856",
  btn_selected_icon_color: "#ffffff",

  box_default_bg_header: "#d1d1d1",
  box_default_primary_text_header: "#000000",
  box_default_secondary_text_header: "#000000",
  box_default_bg: "#ededed",
  box_default_primary_text: "#655563",
  box_default_secondary_text: "#655575",

  box_unselected_bg_header: "#d1d1d1",
  box_unselected_primary_text_header: "#000000",
  box_unselected_secondary_text_header: "#000000",
  box_unselected_bg: "#ededed",
  box_unselected_primary_text: "#655563",
  box_unselected_secondary_text: "#655575",

  box_selected_bg_header: "#0f7864",
  box_selected_primary_text_header: "#ffffff",
  box_selected_secondary_text_header: "#ffffff",
  box_selected_bg: "#ededed",
  box_selected_primary_text: "#4b5563",
  box_selected_secondary_text: "#4b5563",

  btn_payment_text_color: "#ffffff",
  btn_payment_bg_color: "#0f7864",

  bg_image: "",
  bg_image_fixed: false,
  bg_image_repeat: false,
  bg_image_expand: false,
}

let setting: Setting = {
  id: 1,
  software_name: "Space",
  software_description: "Instituição de pagamento para o comércio eletrônico",
  software_color: "#0b6856",
  logo_light: "/logo-light.png",
  logo_dark: "/logo-dark.png",
  favicon_light: "/favicon-light.png",
  favicon_dark: "/favicon-dark.png",
  taxa_cash_in: 1.99,
  taxa_cash_out: 2.99,
  taxa_cash_in_fixa: 0.50,
  taxa_cash_out_fixa: 0.50,
  taxa_reserva: 0.10,
  deposito_minimo: 10.00,
  deposito_maximo: 10000.00,
  saque_minimo: 20.00,
  saque_maximo: 5000.00,
  saques_dia: 3,
  taxa_fixa: 2.00,
  baseline: 100.00,
  valor_min_deposito: 5.00,
  active_taxa_fixa_web: true,
  adquirente_default: "Adquirente X",
  created_at: new Date(),
  updated_at: new Date(),
  image_home: "/image-home.jpg",
  phone_support: "+55 (11) 1234-5678",
  software_color_background: "#ffffff",
  software_color_sidebar: "#f1f1f1",
  software_color_text: "#000000",
  adquirencia: "Adquirência Y",
  cpa: -1,
  rev: -1,
  adquirencia_pix: 'efi',
  adquirencia_billet: 'efi',
  adquirencia_card: 'efi',
  efi_card_env: 'sandbox',
  efi_billet_env: 'sandbox',
  stripe_public: undefined,
  stripe_secret: undefined,

}


export type ConfigStore = {
  device: 'mobile' | 'desktop';
  setDevice: (device: ConfigStore['device']) => void;
  setting: Setting;
  setSetting: (newSetting: Partial<Setting>) => void;
  produto?: Produto;
  setProduto: (produto?: Produto) => void;
  setItemProduto: (items?: { desconto?: number, desconto_bumps?: boolean, desconto_id?: number, desconto_type?: 'percent' | 'fixed' }) => void;
  checkout?: Checkout;
  setCheckout: (checkout?: Checkout) => void;
  vendedor?: string;
  setVendedor: (vendedor?: string) => void;
  config: Config;
  template: Template;
  checkoutData: any | null;
  isLoadingCheckout: boolean;
  error: string | null;
  selectedOrderBumps: string[];
  setConfig: (newConfig: Partial<Config>) => void;
  setTemplate: (newTemplate: Partial<Template>) => void;
  clearCheckoutData: () => void;
  toggleOrderBump: (orderBumpId: string) => void;
  getTotalPrice: () => number;
  getTotalBruto: () => number;
  layout: any
  setLayout: (layout?: any) => void;
  meta_ads: string | null;
  setMetaAds: (meta_ads: string | null) => void;
  fbq: any
  setFbq: (fbq: any) => void
  utmfy: any
  setUtmfy: (utmfy: any) => void
  depoimentos: any[];
  setDepoimentos: (depoimentos: any[]) => void;
  convertDepoimentosToTemplate: (depoimentos: any[]) => any;
  convertTemplateToDepoimentos: (template: any) => any[];
  affiliate_ref: any;
  setAffiliateRef: (affiliate_ref: any) => void;
  stripe?: {
    stripe: Stripe | null
    elements: StripeElement | null
    payload: any
    clientSecret: string
  }
  setStripe?: (stripe?: ConfigStore['stripe']) => void

};

// Tipagem para persistência
type ConfigPersist = (
  config: StateCreator<ConfigStore>,
  options: PersistOptions<ConfigStore, any>
) => StateCreator<ConfigStore>;

const useConfig = create<ConfigStore>(
  (persist as unknown as ConfigPersist)(
    (set, get) => ({
      stripe: undefined,
      setStripe: (stripe: any) =>
        set((state) => ({
          ...state,
          stripe
        })),
      meta_ads: null,
      setMetaAds: (meta_ads) =>
        set((state) => ({
          meta_ads: meta_ads
        })),
      fbq: null,
      setFbq: (fbq) =>
        set((state) => ({
          fbq: fbq
        })),
      utmfy: null,
      setUtmfy: (utmfy) =>
        set((state) => ({
          utmfy: utmfy
        })),
      layout: {},
      setLayout: (layout) =>
        set((state) => ({
          layout: {
            ...state.layout,
            ...layout
          }
        })),
      depoimentos: [],
      setDepoimentos: (depoimentos) =>
        set((state) => ({
          depoimentos: Array.isArray(depoimentos) ? depoimentos : []
        })),
      convertDepoimentosToTemplate: (depoimentos) => {
        return {
          depoimentos: depoimentos?.map((depoimento) => ({
            id: depoimento?.id,
            type: depoimento?.type,
            props: {
              photo: depoimento?.photo || '',
              depoimento: depoimento?.depoimento || '',
              estrelas: depoimento?.estrelas || 5,
              nome: depoimento?.nome || '',
              corFundo: depoimento?.corFundo || '#FFFFFF',
              corTexto: depoimento?.corTexto || '#000000',
              modoHorizontal: depoimento?.modoHorizontal || false,
            }
          }))
        };
      },
      convertTemplateToDepoimentos: (template) => {
        return template?.depoimentos?.map((depoimento: any) => ({
          id: depoimento?.id,
          type: depoimento?.type,
          photo: depoimento?.props?.photo || '',
          depoimento: depoimento?.props?.depoimento || '',
          estrelas: depoimento?.props?.estrelas || 5,
          nome: depoimento?.props?.nome || '',
          corFundo: depoimento?.props?.corFundo || '#FFFFFF',
          corTexto: depoimento?.props?.corTexto || '#000000',
          modoHorizontal: depoimento?.props?.modoHorizontal || false,
        })) || [];
      },
      device: 'desktop',
      setDevice: (device) =>
        set((state) => ({
          device: device
        })),
      setting: setting,
      setSetting: (newSetting) =>
        set((state) => ({
          setting: {
            ...state.setting,
            ...newSetting
          }
        })),
      produto: undefined,
      setProduto: (produto) =>
        set((state) => ({
          produto: {
            ...state?.produto,
            desconto: 0,
            desconto_bumps: false,
            ...produto!
          }
        })),
      setItemProduto: (items) =>
        set((state) => ({
          produto: {
            ...state?.produto,
            desconto: items?.desconto,
            desconto_bumps: items?.desconto_bumps,
            desconto_id: items?.desconto_id,
            desconto_type: items?.desconto_type
          } as any
        })),
      checkout: undefined,
      setCheckout: (checkout) =>
        set((state) => ({
          checkout: {
            ...state?.checkout,
            ...checkout!
          }
        })),
      vendedor: undefined,
      setVendedor: (vendedor) =>
        set((state) => ({
          vendedor: vendedor
        })),
      config: {
        bg_color: "#161c24",
        card_bg_color: "#212b36",
        button_bg_color: "#0b6856",
        border_color: "#3a4651",
        text_color: "white"
      },
      setConfig: (newConfig) =>
        set((state) => ({
          config: {
            ...state.config,
            ...newConfig
          }
        })),
      template: template,
      setTemplate: (newTemplate) =>
        set((state) => ({
          template: {
            ...state.template,
            ...newTemplate
          }
        })),
      checkoutData: null,
      isLoadingCheckout: false,
      error: null,
      selectedOrderBumps: [],
      toggleOrderBump: (orderBumpId: string) => {
        set((state) => ({
          selectedOrderBumps: state.selectedOrderBumps.includes(orderBumpId)
            ? state.selectedOrderBumps.filter(id => id !== orderBumpId)
            : [...state.selectedOrderBumps, orderBumpId]
        }));
      },
      getTotalPrice: () => {

        const state = get();
        if (!state.produto) return 0;

        let total = Number(state.produto.price) || 0;
        let desconto = Number(`${state.produto.desconto}`) || 0;

        // desconto no produto principal
        if (state.produto.desconto_type === 'fixed') {
          total -= desconto;
        } else {
          total -= (total * desconto) / 100;
        }

        // order bumps
        state.selectedOrderBumps.forEach(orderBumpId => {
          const orderBump = state?.produto?.bumps?.find(ob => ob?.id === Number(orderBumpId));
          if (orderBump) {
            let bumpPrice = Number(orderBump?.valor_por);

            if (state.produto?.desconto_bumps) {
              if (state.produto.desconto_type === 'fixed') {
                bumpPrice -= desconto; // aplica desconto fixo no bump
              } else {
                bumpPrice -= (bumpPrice * desconto) / 100; // aplica desconto percentual no bump
              }
            }

            total += bumpPrice;
          }
        });

        return Number(total.toFixed(2)); // retorno final em número, ex: 139.20
      },
      getTotalBruto: () => {

        const state = get();
        if (!state.produto) return 0;

        let total = Number(state.produto.price) || 0;

        state.selectedOrderBumps.forEach(orderBumpId => {
          const orderBump = state?.produto?.bumps?.find(ob => ob?.id === Number(orderBumpId));
          if (orderBump) {
            total += Number(orderBump?.valor_por);
          }
        });
        return Number(total.toFixed(2)); // retorno final em número, ex: 139.20
      },
      clearCheckoutData: () => set({ checkoutData: null, error: null, selectedOrderBumps: [] }),
      affiliate_ref: null,
      setAffiliateRef: (affiliate_ref: any) => {
        set((state) => ({
          ...state,
          affiliate_ref,
        }));
      }
    }),
    {
      name: "config",
      storage: createJSONStorage(() => localStorage),
      partialize: (state) => ({
        device: state.device,
        config: state.config,
        template: state.template,
        layout: state.layout,
        depoimentos: state.depoimentos
      }) as Pick<ConfigStore, 'device' | 'config' | 'template' | 'layout' | 'depoimentos'>
    }
  )
);

export { useConfig };
export default useConfig;
