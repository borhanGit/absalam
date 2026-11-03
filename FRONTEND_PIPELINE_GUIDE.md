# Frontend Design Engineering Rules (Score 98/100 Pipeline)
## যে-কোনো প্রজেক্টে প্রয়োগ করার স্বয়ংসম্পূর্ণ গাইডলাইন (Master Guide)

এই ডকুমেন্টটি অনুসরণ করে আপনি আপনার যে-কোনো বর্তমান (Existing) কিংবা নতুন (New) ওয়েব প্রজেক্টের (Laravel, Next.js, React, Vue ইত্যাদি) ইউজার ইন্টারফেসকে আন্তর্জাতিক মানের **Score 98/100 Pipeline**-এ রূপান্তর করতে পারবেন।

---

## সূচিপত্র
1. [ধাপ ১: প্রয়োজনীয় Agent Skills ইনস্টলেশন](#ধাপ-১-প্রয়োজনীয়-agent-skills-ইনস্টলেশন)
2. [ধাপ ২: Playwright MCP কনফিগারেশন](#ধাপ-২-playwright-mcp-কনফিগারেশন)
3. [ধাপ ৩: প্রজেক্ট রুট রুলস সেটআপ (CLAUDE.md / AGENTS.md)](#ধাপ-৩-প্রজেক্ট-রুট-রুলস-সেটআপ-claudemd--agentsmd)
4. [ধাপ ৪: ডিজাইন সিস্টেম গ্রাউন্ডিং (DESIGN.md তৈরি)](#ধাপ-৪-ডিজাইন-সিস্টেম-গ্রাউন্ডিং-designmd-তৈরি)
5. [ধাপ ৫: গ্লোবাল সিএসএস ও কম্পোনেন্ট টোকেন (CSS Foundation)](#ধাপ-৫-গ্লোবাল-সিএসএস-ও-কম্পোনেন্ট-টোকেন-css-foundation)
6. [ধাপ ৬: অ্যান্টি-স্লপ গভর্ন্যান্স (Anti-Slop Execution Rules)](#ধাপ-৬-অ্যান্টি-স্লপ-গভর্ন্যান্স-anti-slop-execution-rules)
7. [ধাপ ৭: ক্লোজড-লুপ ব্রাউজার কিউএ (Closed-Loop Browser QA)](#ধাপ-৭-ক্লোজড-লুপ-ব্রাউজার-কিউএ-closed-loop-browser-qa)
8. [প্রজেক্ট রূপান্তরের আদর্শ ওয়ার্কফ্লো চেকলিস্ট](#প্রজেক্ট-রূপান্তরের-আদর্শ-ওয়ার্কফ্লো-চেকলিস্ট)

---

## ধাপ ১: প্রয়োজনীয় Agent Skills ইনস্টলেশন

আপনার প্রজেক্টের টার্মিনালে নিচের কমান্ডগুলো রান করে ডিজাইন ইঞ্জিনিয়ারিং সংক্রান্ত স্কিলগুলো যুক্ত করুন:

```bash
# ১. Impeccable Skill (ডিজাইন অডিট, পলিশ এবং টোকেন স্পেসিফিকেশন)
npx skills add impeccable

# ২. Taste Skill v2 (অ্যান্টি-স্লপ ও আন্তর্জাতিক মানের আর্ট ডিরেকশন)
npx skills add Leonxlnx/taste-skill

# ৩. Vercel Web Design Guidelines (অ্যাক্সেসিবিলিটি ও ইউআই বেস্ট প্র্যাকটিস)
npx skills add https://github.com/vercel-labs/agent-skills --skill web-design-guidelines
```

> **টিপস:** এই স্কিলগুলো আপনার AI এজেন্টের কোড তৈরির রুচি ও মান উন্নত করে জেনেরিক বা কৃত্রিম ("AI-generated / sloppy") লুক দূর করে।

---

## ধাপ ২: Playwright MCP কনফিগারেশন

এজেন্ট যাতে নিজেই কোড পরিবর্তনের পর ব্রাউজার ওপেন করে স্ক্রিনশট নিয়ে ইউআই পরীক্ষা করতে পারে, সেজন্য Playwright MCP সক্রিয় করুন।

### Claude CLI বা Antigravity / Cursor-এ কনফিগার:
```bash
claude mcp add playwright npx '@playwright/mcp@latest'
```

অথবা গ্লোবাল কনফিগ ফাইলে (`~/.gemini/config/mcp_config.json` বা `claude_desktop_config.json`):
```json
{
  "mcpServers": {
    "playwright": {
      "command": "npx",
      "args": ["-y", "@playwright/mcp@latest"]
    }
  }
}
```

---

## ধাপ ৩: প্রজেক্ট রুট রুলস সেটআপ (CLAUDE.md / AGENTS.md)

আপনার প্রজেক্টের রুট ডিরেক্টরিতে `CLAUDE.md` অথবা `AGENTS.md` (কিংবা `.cursorrules`) ফাইলে নিচের নিয়মাবলী যোগ করুন:

```markdown
# Frontend Design Engineering Rules (Score 98/100 Pipeline)

1. DESIGN SYSTEM GROUNDING (Awesome DESIGN.md):
- Always read `./DESIGN.md` before creating or modifying any UI component.
- Strictly enforce the exact hex colors, typography scale, spacing tokens, border radius, and shadow tokens defined in `./DESIGN.md`.

2. ANTI-SLOP GOVERNANCE (Impeccable & Taste-Skill):
- Never use generic purple-to-blue gradient text headlines or glowing candy buttons.
- Never place emoji icon tiles in UI headings, buttons, or navigation tabs; use clean inline SVGs instead.
- Calibrate Taste parameters: BOLD (Design Variance), MOTION (Micro-interactions), and DENSITY (Visual Density).

3. UX & ACCESSIBILITY AUDIT (Vercel Web Design Guidelines):
- Every interactive button or icon must have a minimum hit target of 24x24px (44x44px for mobile touch targets).
- Never disable browser zoom (`user-scalable=no` or `maximum-scale=1` is strictly forbidden).
- Every async form submission or action button must display an explicit loading state (`<Spinner />`) while pending.

4. CLOSED-LOOP BROWSER QA (Microsoft Playwright MCP):
- After building or updating any page, open the live localhost URL using Playwright MCP.
- Click interactive buttons, take real browser screenshots (`take_screenshot`), verify that no CTA overflows its card or overlaps text, and fix all visual bugs before completing the task.
```

---

## ধাপ ৪: ডিজাইন সিস্টেম গ্রাউন্ডিং (Awesome DESIGN.md ও Stripe প্রিসেট)

প্রজেক্টের রুটে একটি `./DESIGN.md` ফাইল থাকা বাধ্যতামূলক। এটি AI-কে আপনার নির্ধারিত গাইডলাইন ও কালার প্যালেট কঠোরভাবে অনুসরণ করতে বাধ্য করে।

### এই প্রজেক্টে ব্যবহৃত প্রিসেট: **Stripe Design System (Awesome DESIGN.md)**
আমাদের বর্তমান প্রজেক্টে ব্যবহৃত হয়েছে **Awesome DESIGN.md** কালেকশনের বিশ্বখ্যাত **Stripe Inspired Design** প্রিসেট (`Stripi-Inspired-design-analysis`)।

#### কেন Stripe প্রিসেট ফিনটেক ও ERP-এর জন্য সেরা?
1. **Electric Indigo (`#533afd`) & Deep Navy Ink (`#0d253d`):** সাধারণ নীল বা বেগুনি রঙের চেয়ে অনেক বেশি প্রিমিয়াম ও বিশ্বাসযোগ্য লুক দেয়।
2. **Tabular Numerics (`tnum`):** টাকা ও হিসাবের সংখ্যার জন্য ফিক্সড-উইডথ কলাম বজায় রাখে, ফলে বড় বড় লেজার ও চালান পড়তে কোনো মিসঅ্যালাইনমেন্ট হয় না।
3. **Pill Buttons & Blur Glass Cards:** আধুনিক ও পরিষ্কার কন্ট্রাস্ট তৈরি করে।

### অন্যান্য প্রজেক্টে যেভাবে Awesome DESIGN.md থেকে প্রিসেট সংগ্রহ করবেন:

| প্রজেক্টের ধরন | প্রস্তাবিত Awesome DESIGN.md প্রিসেট | বিশেষ বৈশিষ্ট্য |
| :--- | :--- | :--- |
| **POS, ERP, ফিনটেক, বিলিং ও হিসাব** | **Stripe (বর্তমান প্রজেক্ট)** | Deep Navy, Electric Indigo, Tabular Numbers, Pill Buttons |
| **SaaS, টাস্ক ট্র্যাকার, প্রোডাক্টিভিটি** | **Linear** | Ultra-Dark (#08090a), Violet Accent, High Density, Keyboard-first |
| **ডেভেলপার টুলস, টেক পোর্টাল** | **Vercel / Next.js** | Monochromatic Black & White, Razor Sharp Borders, Inter Font |
| **ই-কমার্স ও কনজিউমার অ্যাপ** | **Apple / Airbnb** | Soft Warm Grays, Large Rounded Cards, Generous Padding |

> [!TIP]
> **ব্যবহারের নিয়ম:** Awesome DESIGN.md রিপোজিটরি থেকে আপনার পছন্দমতো স্টাইল কপি করে প্রজেক্টের রুটে `./DESIGN.md` নামে সেভ করুন। AI স্বয়ংক্রিয়ভাবে সেখান থেকে কালার কোড ও ফন্ট রুলস রিড করে কোডে প্রয়োগ করবে।


---

## ধাপ ৫: গ্লোবাল সিএসএস ও কম্পোনেন্ট টোকেন (CSS Foundation)

আপনার মূল সিএসএস ফাইলে (`resources/css/app.css` বা `src/index.css`) পুনঃব্যবহারযোগ্য ক্লাস যুক্ত করুন:

```css
/* Tabular Numbers for Perfect Accounting Alignment */
.tnum {
    font-feature-settings: "tnum";
    font-variant-numeric: tabular-nums;
}

/* Unified Card Shell */
.card-shell {
    background-color: rgba(13, 22, 38, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 0.75rem; /* 12px */
    backdrop-filter: blur(12px);
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.card-shell:hover {
    border-color: rgba(255, 255, 255, 0.14);
}

/* Primary Pill Button */
.btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1.25rem;
    border-radius: 9999px;
    background-color: #533afd;
    color: #ffffff;
    font-size: 0.75rem; /* 12px */
    font-weight: 600;
    transition: background-color 0.15s ease, transform 0.05s ease;
}

.btn-primary:hover {
    background-color: #432cd9;
}

.btn-primary:active {
    transform: scale(0.98);
}

/* Secondary Action Button */
.btn-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    background-color: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #cbd5e1;
    font-size: 0.75rem;
    font-weight: 500;
    transition: all 0.15s ease;
}

.btn-secondary:hover {
    background-color: rgba(255, 255, 255, 0.1);
    color: #ffffff;
}
```

---

## ধাপ ৬: অ্যান্টি-স্লপ গভর্ন্যান্স (Anti-Slop Execution Rules)

যেকোনো ভিউ বা কম্পোনেন্ট তৈরির সময় নিচের ৫টি গোল্ডেন রুল কঠোরভাবে মানতে হবে:

| ❌ আগের স্লপ (Slop) | ✅ নতুন প্রফেশনাল মানদণ্ড (Score 98/100) |
| :--- | :--- |
| হেডারে ইমোজি (যেমন: `<span>💵</span> মানি রিসিট`) | ক্লিন ইনলাইন এসভিজি (`<svg class="w-4 h-4 text-indigo-400">...`) |
| সস্তা গ্রেডিয়েন্ট (`bg-gradient-to-r from-purple-600 to-pink-600`) | সুনির্দিষ্ট ব্র্যান্ড কালার (`bg-indigo-600 hover:bg-indigo-500`) |
| ছড়ানো-ছিটানো ফন্ট সাইজ ও ভিন্ন বর্ডার | নির্ধারিত স্কেল (`text-xs text-slate-400 font-semibold`) এবং `.card-shell` |
| আঁকাবাঁকা সংখ্যা ও অসামঞ্জস্য কারেন্সি | ফন্ট মোনো ও ট্যাবুলার নাম্বার ক্লাস (`font-mono tnum`) সহ `৳` |
| ক্লিক করলে কোনো রেসপন্স ছাড়া হ্যাং হওয়া | লোডিং স্পিনার ও বাটন ডিসেবল স্টেট (`<Spinner />`) |

---

## ধাপ ৭: ক্লোজড-লুপ ব্রাউজার কিউএ (Closed-Loop Browser QA)

কাজ শেষ করার আগে নিচের পদক্ষেপগুলো অনুসরণ করতে হবে:

1. **অ্যাসেট বিল্ড:**
   ```bash
   npm run build
   ```
2. **সার্ভার সচল করা:**
   ```bash
   php artisan serve  # অথবা npm run dev
   ```
3. **প্লে-রাইট দিয়ে অটোমেটিক পেজ পরিদর্শন:**
   - ব্রাউজারে পেজ ওপেন করুন: `browser_navigate(url: 'http://localhost:8000/...')`
   - স্ক্রিনশট গ্রহণ করুন: `browser_take_screenshot()`
   - স্ক্রিনশট পরীক্ষা করে নিশ্চিত করুন:
     - কোনো টেবিলের হেডার বা কলাম ব্রেক করছে কি না।
     - কোনো বাটন বা কার্ডের বাইরে টেক্সট ওভারফ্লো হচ্ছে কি না।
     - মোবাইল ও ডেস্কটপ দুই ভিউতেই টেক্সট পড়তে আরামদায়ক কি না।

---

## প্রজেক্ট রূপান্তরের আদর্শ ওয়ার্কফ্লো চেকলিস্ট

নতুন বা বিদ্যমান যে-কোনো প্রজেক্টে এই প্রম্পটটি AI এজেন্টকে দিয়ে শুরু করুন:

> *"আমাদের প্রজেক্টে Score 98/100 Frontend Pipeline প্রয়োগ করো। প্রথমে DESIGN.md পড়ে নাও, তারপর CSS টোকেন নিশ্চিত করে প্রতিটি ভিউ থেকে সমস্ত ইমোজি ও স্লপ গ্র্যাডিয়েন্ট দূর করে ক্লিন Lucide SVG, .card-shell, .btn-primary এবং tnum প্রয়োগ করো। সবশেষে Playwright দিয়ে স্ক্রিনশট নিয়ে ইউআই ভেরিফাই করো।"*
