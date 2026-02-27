export interface Template {
  theme?: 'custom' | 'light' | 'dark';
  font?: 'Roboto' | 'Open Sans';
  text_primary?: string;
  text_secondary?: string;
  text_active?: string;
  icon_color?: string;
  bg?: string;
  bg_form_payment?: string;

  btn_unselected_text_color?: string;
  btn_unselected_bg_color?: string;
  btn_unselected_icon_color?: string;

  btn_selected_text_color?: string;
  btn_selected_bg_color?: string;
  btn_selected_icon_color?: string;

  box_default_bg_header?: string;
  box_default_primary_text_header?: string;
  box_default_secondary_text_header?: string;
  box_default_bg?: string;
  box_default_primary_text?: string;
  box_default_secondary_text?: string;

  box_unselected_bg_header?: string;
  box_unselected_primary_text_header?: string;
  box_unselected_secondary_text_header?: string;
  box_unselected_bg?: string;
  box_unselected_primary_text?: string;
  box_unselected_secondary_text?: string;

  box_selected_bg_header?: string;
  box_selected_primary_text_header?: string;
  box_selected_secondary_text_header?: string;
  box_selected_bg?: string;
  box_selected_primary_text?: string;
  box_selected_secondary_text?: string;

  btn_payment_text_color?: string;
  btn_payment_bg_color?: string;

  bg_image?: string;
  bg_image_fixed?: boolean;
  bg_image_repeat?: boolean;
  bg_image_expand?: boolean;
  
  depoimentos?: any[];
}