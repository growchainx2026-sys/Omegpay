export interface Setting {
	id: number
	software_name: string
	software_description: string
	software_color: string
	logo_light: string
	logo_dark: string
	favicon_light: string
	favicon_dark: string
	taxa_cash_in: number
	taxa_cash_out: number
	taxa_cash_in_fixa: number
	taxa_cash_out_fixa: number
	taxa_reserva: number
	deposito_minimo: number
	deposito_maximo: number
	saque_minimo: number
	saque_maximo: number
	saques_dia: number
	taxa_fixa: number
	baseline: number
	valor_min_deposito: number
	active_taxa_fixa_web: boolean
	adquirente_default: string
	created_at: Date
	updated_at: Date
	image_home: string
	phone_support: string
	software_color_background: string
	software_color_sidebar: string
	software_color_text: string
	adquirencia: string
	cpa: number
	rev: number
	adquirencia_pix: string
	adquirencia_billet: string
	adquirencia_card: string
	efi_id_account?: string
	efi_card_env: 'production' | 'sandbox'
	efi_billet_env: 'production' | 'sandbox'
	stripe_secret?: string;
	stripe_public?: string;
}