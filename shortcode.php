<?php
function web86_shortcode_function()
{
  $output = '';
  $output_html = '';

  // Получить значение поля из метабокса
  $procces_arr = get_post_meta(get_the_ID(), 'web86_field_name', true);

  // Проверить, есть ли значение
  if (!empty($procces_arr)) {

    $output_html .= '<div class="roadmap gradien-gray wp-block-group"><div style="width:100%">';

    foreach ($procces_arr[0] as $razdel) {
      $output_html .= '<div class="razdel"><h3>' . $razdel['field1'] . '</h3></div><ul class="steps-box">';

      foreach ($razdel['sub_fields'] as $subfield) {
        if (isset($razdel['field2']) && !empty($razdel['field2'])) {
          $output_html .= '<li style="--accent-color:' . $razdel['field2'] . '">';
        } else {
          // Если поле field2 отсутствует или пустое, выполнится этот блок кода
          $output_html .= '<li style="--accent-color:#4795EC">';
        }
        $output_html .= '<div class="date" >' . $subfield['sub_field_1'] . '</div><div class="title">' . $subfield['sub_field_2'] . '</div><div class="descr subtitle">' . $subfield['sub_field_3'] . '</div><div class="descr last">' . $subfield['sub_field_4'] . '</div></li>';

      }
      $output_html .= '</ul>';
    }
    $output_html .= '<div class="finish">' . $procces_arr[1] . '</div></div></div>';
    $output_css = '<style>
                body {
                  --color: rgba(30, 30, 30);
                  --bgColor: rgba(245, 245, 245);
                }
                .entry-content ul.steps-box>li:before {display:none;}
                .roadmap .finish {width:330px;margin:0 auto 30px auto;border:1px solid #000;padding:40px;}
                .roadmap ul.steps-box {
                  margin:0;
                  --col-gap: 2rem;
                  --row-gap: 2rem;
                  --line-w: 0.25rem;
                  display: grid;
                  grid-template-columns: var(--line-w) 1fr;
                  grid-auto-columns: max-content;
                  column-gap: var(--col-gap);
                  list-style: none;
                  width: 100%;
                  /* width: min(60rem, 90%);*/
                  margin-inline: auto;
                }
                .roadmap ul.steps-box:last-of-type {
                    margin-bottom:0;
                    padding-bottom:0;
                }
                
                /* line */
                .roadmap ul.steps-box::before {
                  content: "";
                  grid-column: 1;
                  grid-row: 1 / span 20;
                  background: #000;
                  border-radius: calc(var(--line-w) / 2);
                }
                
                /* columns*/
                
                /* row gaps */
                .roadmap ul.steps-box > li:not(:last-child) {
                  margin-bottom: var(--row-gap);
                }
                .roadmap ul.steps-box > li:first-child {
                  padding-top:20px;
                }
                
                /* card */
                .roadmap ul.steps-box > li {
                  margin:0;
                  grid-column: 2;
                  --inlineP: 1.5rem;
                  margin-inline: var(--inlineP);
                  grid-row: span 2;
                  display: grid;
                  grid-template-rows: min-content min-content min-content;
                  padding:0;
                }
                
                /* date */
                .roadmap ul.steps-box > li .date {
                  --dateH: 3rem;
                  height: var(--dateH);
                  margin-inline: calc(var(--inlineP) * -1);
                
                  text-align: center;
                  background-color: var(--accent-color);
                
                  color: white;
                  font-size: 20px;
                  font-weight: 700;
                
                  display: grid;
                  place-content: center;
                  position: relative;
                
                  border-radius: calc(var(--dateH) / 2) 0 0 calc(var(--dateH) / 2);
                }
                /* custom color li inside li */
                .roadmap ul.steps-box > li ul li:before{
                  background: var(--accent-color);
                }
                
                /* date flap */
                .roadmap ul.steps-box > li .date::before {
                  content: "";
                  width: var(--inlineP);
                  aspect-ratio: 1;
                  background: var(--accent-color);
                  background-image: linear-gradient(rgba(0, 0, 0, 0.2) 100%, transparent);
                  position: absolute;
                  top: 100%;
                
                  clip-path: polygon(0 0, 100% 0, 0 100%);
                  right: 0;
                }
                
                /* circle */
                .roadmap ul.steps-box > li .date::after {
                  content: "";
                  position: absolute;
                  width: 1rem;
                  height: 1rem;
                  aspect-ratio: 1;
                  background: var(--bgColor);
                  border: 0.3rem solid var(--accent-color);
                  border-radius: 50%;
                  top: 50%;
                
                  transform: translate(50%, -50%);
                  right: calc(100% + var(--col-gap) + var(--line-w) / 2);
                }
                
                /* title descr */
                .roadmap ul.steps-box > li .title,
                .roadmap ul.steps-box > li .descr {
                  background: var(--bgColor);
                  position: relative;
                  padding-inline: 1.5rem;
                  line-height:1.2em;
                }
                .roadmap ul.steps-box > li .title {
                  overflow: hidden;
                  padding-block-start: 1.5rem;
                  padding-block-end: 1rem;
                  font-weight: 500;
                  font-size: 24px;
                }
                .roadmap ul.steps-box > li .descr {
                  padding-block-end: 0.5rem;
                  font-weight: 300;
                  font-size: 16px;
                }
                .roadmap ul.steps-box > li .descr.subtitle {
                    font-size: 16px;
                    font-weight:600;
                  }
                
                
                /* shadows */
                .roadmap ul.steps-box > li .title::before,
                .roadmap ul.steps-box > li .descr::before {
                  content: "";
                  position: absolute;
                  width: 90%;
                  height: 0.5rem;
                  background: rgba(0, 0, 0, 0.5);
                  left: 50%;
                  border-radius: 50%;
                  filter: blur(4px);
                  transform: translate(-50%, 50%);
                }
                .roadmap ul.steps-box > li .title::before {
                  bottom: calc(100% + 0.125rem);
                }
                
                .roadmap ul.steps-box > li .descr::before {
                  z-index: -1;
                  bottom: 0.25rem;
                }
                .roadmap ul.steps-box:last-child {  margin-bottom:0;}
                
                @media (min-width: 40rem) {
                  .roadmap ul.steps-box {
                    grid-template-columns: 1fr var(--line-w) 1fr;
                  }
                  .roadmap ul.steps-box::before {
                    grid-column: 2;
                  }
                  .roadmap ul.steps-box > li:nth-child(even) {
                    grid-column: 1;
                  }
                  .roadmap ul.steps-box > li:nth-child(odd) {
                    grid-column: 3;
                  }
                
                  /* start second card */
                  .roadmap ul.steps-box > li:nth-child(2) {
                    grid-row: 2/4;
                  }
                
                  .roadmap ul.steps-box > li:nth-child(even) .date::before {
                    clip-path: polygon(0 0, 100% 0, 100% 100%);
                    left: 0;
                  }
                
                  .roadmap ul.steps-box > li:nth-child(even) .date::after {
                    transform: translate(-50%, -50%);
                    left: calc(100% + var(--col-gap) + var(--line-w) / 2);
                  }
                  .roadmap ul.steps-box > li:nth-child(even) .date {
                    border-radius: 0 calc(var(--dateH) / 2) calc(var(--dateH) / 2) 0;
                  }
                  .roadmap .razdel {text-align:center}
                  ul.steps-box > li:last-child .descr.last {
                    margin-bottom:40px;
                  }
                  .roadmap ul.steps-box > li .descr.last {
                    padding-bottom:20px;
                  }
                  .roadmap .razdel h3 {display: inline-block;text-transform: uppercase;font-size: 26px;margin-top: 0;line-height: 1.3em;font-weight: 300;}
                  .roadmap > div {counter-reset: my-counter;}
                  .roadmap .razdel::before {
                    display:inline-block;
                    counter-increment: my-counter;
                    content: "0"counter(my-counter)". "; 
                    font-size: 36px;font-weight: 600;margin-right: 10px;
                  }
                }
                @media (max-width: 40rem) {
                   .roadmap ul.steps-box {
                    margin: 0;
                    width:100%;
                    padding-left: 34px;
                  }
                  .roadmap ul.steps-box::before {
                    width:1px;
                  }
                  .roadmap ul.steps-box > li .date::before {
                    display:none;
                  }
                  .roadmap ul.steps-box > li .date {
                    border-radius: calc(var(--dateH) / 2);
                    height: auto;padding: 8px 25px;font-size: 16px;
                    display:inline-block;
                    font-size:18px;
                    font-weight:600;
                  }
                  .roadmap ul.steps-box > li {
                    --inlineP: 0;
                    margin-inline: 0;
                    display:block;
                  }
                  .roadmap ul.steps-box > li .title, ul.steps-box > li .descr {
                    background:none;
                    padding-inline: 0;
                  }
                  .roadmap ul.steps-box > li .title {
                    padding-block-start: 0;
                    padding-block-end: 0;
                    font-size: 22px;
                    margin: 12px 0 6px 0;
                    font-weight:600;
                  }
                  .roadmap ul.steps-box > li .title::before {
                    display:none;
                  }
                  .roadmap ul.steps-box > li .descr {
                    padding-block-start: 0;
                    padding-block-end: 0;
                    font-size: 16px;
                    font-weight:300;
                    margin: 7px 0;
                    padding:0;
                    background: none;
                  }
                  .roadmap ul.steps-box > li .descr.subtitle {
                    font-size: 16px;
                    font-weight:600;
                  }
                  .roadmap ul.steps-box > li:last-child .descr.last {
                    padding-bottom:50px;
                  }
                  .roadmap ul.steps-box > li .date::after {
                    right: calc(100% + var(--col-gap) + var(--line-w) / 1.15);
                    background:#000;
                    border:none;
                    width: 0.5rem;height: 0.5rem;
                  }
                  .roadmap .razdel h3 {
                    font-size:24px;
                    font-weight:300;
                    margin-bottom:0;
                    padding-left:10px;
                    margin-top: 0;
                    line-height: 1.2em;
                    
                  }
                  .roadmap > div {counter-reset: my-counter;}
                  .roadmap .razdel::before {
                    display:flex;
                    counter-increment: my-counter;
                    content: "0"counter(my-counter); 
                    background:url(' . plugins_url("timeline-web86") . '/images/polygon.svg) no-repeat center center;
                    background-size:contain;
                    width: 70px;
                    align-items: center;
                    color: #fff;
                    height:79px;
                    font-size:30px;
                    justify-content:center;
                    font-weight:300;
                    flex-shrink:0;
                  }
                  .roadmap .razdel {
                    display: flex;align-items: center;
                  }
                  .roadmap .finish {
                    width: 300px;
                    margin: 0 auto 30px auto;
                    border: 1px solid #000;
                    padding: 20px 40px;
                    margin-left: 0;
                  }
                }
      </style>';
    $output = $output_css . $output_html;
  } else {
    $output = '<p style="color:red">No timelines found for this article</p>';
  }

  return $output;
}