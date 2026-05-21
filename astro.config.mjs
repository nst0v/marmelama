import { defineConfig } from 'astro/config';
import sitemap from '@astrojs/sitemap';

export default defineConfig({
  site: 'https://nst0v.github.io',
  base: '/marmelama/',
  integrations: [sitemap()],
  output: 'static'
});
