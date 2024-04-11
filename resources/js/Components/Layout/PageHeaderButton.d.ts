import { FontAwesomeIconProps } from "../Icons/FontAwesomeIconProps";

export interface PageHeaderButton {
  text: string;
  onClick: () => void;
  additionalClasses?: string;
  disabled?: boolean;
  icon?: FontAwesomeIconProps;
  href?: string;
}
