---
name: Apex Forge
colors:
  surface: '#111125'
  surface-dim: '#111125'
  surface-bright: '#37374d'
  surface-container-lowest: '#0c0c1f'
  surface-container-low: '#1a1a2e'
  surface-container: '#1e1e32'
  surface-container-high: '#28283d'
  surface-container-highest: '#333348'
  on-surface: '#e2e0fc'
  on-surface-variant: '#e7bdb2'
  inverse-surface: '#e2e0fc'
  inverse-on-surface: '#2f2e43'
  outline: '#ad887e'
  outline-variant: '#5d4038'
  surface-tint: '#ffb5a0'
  primary: '#ffb5a0'
  on-primary: '#601400'
  primary-container: '#ff5625'
  on-primary-container: '#541100'
  inverse-primary: '#b12d00'
  secondary: '#ffd660'
  on-secondary: '#3d2f00'
  secondary-container: '#e8b800'
  on-secondary-container: '#5f4a00'
  tertiary: '#74db9d'
  on-tertiary: '#00391f'
  tertiary-container: '#3aa36b'
  on-tertiary-container: '#00311a'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#ffdbd1'
  primary-fixed-dim: '#ffb5a0'
  on-primary-fixed: '#3b0900'
  on-primary-fixed-variant: '#872000'
  secondary-fixed: '#ffe08e'
  secondary-fixed-dim: '#f1c014'
  on-secondary-fixed: '#241a00'
  on-secondary-fixed-variant: '#584400'
  tertiary-fixed: '#91f8b8'
  tertiary-fixed-dim: '#74db9d'
  on-tertiary-fixed: '#002110'
  on-tertiary-fixed-variant: '#00522f'
  background: '#111125'
  on-background: '#e2e0fc'
  surface-variant: '#333348'
  surface-card: '#16213E'
  surface-accent: '#0F3460'
  admin-sidebar: '#0F1724'
  danger-red: '#E53E3E'
  text-primary: '#FFFFFF'
  text-secondary: '#A0AEC0'
typography:
  display-hero:
    fontFamily: Bebas Neue
    fontSize: 96px
    fontWeight: '700'
    lineHeight: 100%
    letterSpacing: 0.02em
  display-hero-mobile:
    fontFamily: Bebas Neue
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 110%
    letterSpacing: 0.02em
  headline-lg:
    fontFamily: Bebas Neue
    fontSize: 48px
    fontWeight: '400'
    lineHeight: 120%
    letterSpacing: 0.05em
  headline-md:
    fontFamily: Bebas Neue
    fontSize: 32px
    fontWeight: '400'
    lineHeight: 120%
    letterSpacing: 0.05em
  title-lg:
    fontFamily: DM Sans
    fontSize: 20px
    fontWeight: '700'
    lineHeight: 140%
  body-lg:
    fontFamily: DM Sans
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 160%
  body-md:
    fontFamily: DM Sans
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 160%
  label-md:
    fontFamily: DM Sans
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 100%
    letterSpacing: 0.01em
  data-mono:
    fontFamily: JetBrains Mono
    fontSize: 14px
    fontWeight: '500'
    lineHeight: 140%
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  base: 8px
  container-max: 1440px
  gutter: 24px
  margin-mobile: 16px
  stack-sm: 4px
  stack-md: 12px
  stack-lg: 24px
---

## Brand & Style

The design system embodies a **Premium Dark Industrial** aesthetic, specifically tailored for the high-performance motorcycle sector. It targets enthusiasts who value precision, speed, and technical excellence. The UI evokes the atmosphere of a world-class racing garage—dark, focused, and high-tech, punctuated by vibrant technical accents.

The style is a fusion of **Corporate Modern** structure and **High-Contrast Sporty** energy. It utilizes deep, layered navy-blacks to create an immersive environment where machine components and professional services are elevated to premium status. Visual cues like technical grids, subtle noise textures, and precision-engineered border treatments reinforce the "industrial" narrative without sacrificing the "premium" user experience.

- **Tone**: Aggressive, precise, professional, and mechanical.
- **Language**: Indonesian (Bahasa Indonesia) for localized trust.
- **Currency**: Rupiah (IDR), formatted for high-value transactions.

## Colors

This is a **Dark Mode primary** design system. The palette is engineered for high visibility and emotional impact within a professional industrial context.

- **Primary (#FF4500)**: "Ignition Orange." Used strictly for primary actions, focus states, and critical brand identifiers. It represents heat, energy, and the "start" of the journey.
- **Secondary (#E8B800)**: "Race Gold." Reserved for premium status indicators, "PROMO" badges, and star ratings. It adds a layer of luxury to the industrial base.
- **Tertiary (#38A169)**: "Track Green." Used for success states and specifically for WhatsApp integration to ensure brand recognition for customer support.
- **Neutral/Background**: The foundation is a deep navy-black sequence. `#1A1A2E` is the canvas, while `#16213E` and `#0F3460` provide structural depth for cards and sections.

## Typography

The typography strategy employs a "Mechanical vs. Humanist" contrast. 

- **Headlines**: Use **Bebas Neue**. Its condensed, all-caps nature mimics vintage racing posters and industrial signage. It should be used for impact and structural hierarchy.
- **Body & UI**: Use **DM Sans**. This provides a sophisticated, clean, and highly legible counterpoint to the aggressive headlines. It ensures that technical details and pricing remain readable.
- **Data**: For order IDs, Part Numbers, and IDR currency strings, a Monospace font (JetBrains Mono) is recommended to reinforce the technical/garage aesthetic.
- **Currency**: Always prefix with "Rp" followed by a space and period grouping (e.g., Rp 1.500.000).

## Layout & Spacing

The system uses a **Fixed Grid** model for desktop to maintain the "contained" feel of a premium catalog, while transitioning to a fluid model for mobile devices.

- **Desktop**: 12-column grid with a 1440px max-width. Gutters are set to 24px to provide "breathing room" between dense technical components.
- **Mobile**: Single-column or 2-column (for product grids) with 16px side margins.
- **Rhythm**: All spacing is derived from an 8px base unit. Component internal padding should favor 16px (2x) or 24px (3x) to maintain a spacious, premium feel despite the dark palette.
- **Reflow**: On tablet (768px - 1024px), sidebar navigation should collapse into a "compact" mode or a bottom-bar/hamburger drawer to prioritize content area.

## Elevation & Depth

Depth in this design system is achieved through **Tonal Layering** and **Subtle Glassmorphism** rather than traditional heavy shadows.

- **Base Layer**: Background Utama (`#1A1A2E`).
- **Mid Layer**: Surface/Card (`#16213E`). These surfaces should have a 1px border using a slightly lighter navy or a 10% opacity white to define edges against the background.
- **Top Layer**: Modals and sticky Navbars. Use a subtle backdrop blur (12px - 20px) with 80% opacity of the background color to create a "tinted glass" effect.
- **Interaction**: On hover, cards should lift using a very subtle, large-radius shadow tinted with the primary color at 5% opacity (e.g., `0px 20px 40px rgba(255, 69, 0, 0.05)`). This creates a "glow" effect rather than a traditional shadow.

## Shapes

The shape language is **Soft (0.25rem / 4px)**. This mimics the precision-machined edges of engine components—not sharp enough to be dangerous, but not rounded enough to lose its aggressive industrial edge.

- **Buttons & Inputs**: 4px radius.
- **Cards**: 8px (rounded-lg) to provide a clear container definition.
- **Status Badges**: Pill-shaped (fully rounded) to contrast against the structural rigidity of the rest of the UI.
- **Icons**: Use a "duotone" or "outline" style with a 2px stroke weight to match the technicality of the typography.

## Components

- **Buttons**:
  - **Primary**: Filled `#FF4500` with White text. Bold, all-caps Bebas Neue for the label.
  - **Secondary**: Outlined 2px in `#A0AEC0`.
  - **WhatsApp**: Filled `#38A169` with a pulse animation ring to draw attention.
- **Inputs**: Dark background (`#0F1724`) with a 1px border. On focus, the border transitions to `#FF4500` with a 2px thickness.
- **Cards**: Surface color `#16213E`. For "Featured" items, add a 2px top-border in `#FF4500`. Product images should be centered with a subtle "under-glow" shadow.
- **Chips/Badges**:
  - `PROMO`: Gold background, black text.
  - `TERLARIS`: Red background, white text.
  - `STOK HABIS`: 60% opacity overlay on the entire card with center-aligned text.
- **Lists**: Used for service menus. Items separated by a thin 1px line (`rgba(255,255,255,0.05)`). Highlighting an item changes the background to `#0F3460`.
- **Specialty Components**:
  - **Stats Bar**: Horizontal bar with `#0F3460` background and animated counters for "Servis Selesai", "Pelanggan Puas", etc.
  - **Progress Tracker**: For motorcycle service status, using a vertical line with `#FF4500` nodes.