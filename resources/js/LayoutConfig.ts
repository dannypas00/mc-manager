import logo from '../images/icons/MCM-logo.webp';

interface NavigationItem {
  name: string;
  route: string;
  icon: string;
}

const layoutNavigationItems: NavigationItem[] = [
  { name: 'Dashboard', route: 'pages.home', icon: 'home' },
];

const userNavigationItems: NavigationItem[] = [
  { name: 'Profile', route: 'pages.me.profile', icon: 'user' },
  { name: 'Settings', route: 'pages.me.settings', icon: 'sliders' },
  { name: 'Sign out', route: 'logout', icon: 'arrow-right-from-bracket' },
];

const appName: string = 'MC Manager';
const appLogo: string = logo;

export {
  layoutNavigationItems,
  userNavigationItems,
  NavigationItem,
  appName,
  appLogo,
};
