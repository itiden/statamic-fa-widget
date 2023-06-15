/**
 * When extending the control panel, be sure to uncomment the necessary code for your build process:
 * https://statamic.dev/extending/control-panel
 */

import FA from "./components/widgets/FA.vue";

Statamic.booting(() => {
  Statamic.$components.register("fa-widget", FA);
});
