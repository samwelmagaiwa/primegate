from pathlib import Path
path = Path('index-2.html')
text = path.read_text()
def replace(old, new):
    global text
    if old not in text:
        raise SystemExit(f'Missing segment: {old[:60]}')
    text = text.replace(old, new, 1)

replace(
"<p>Customs clearance work involves preparation and submission of documentations required to facilitate export or imports into the country, representing the client during customs examination, assessment, payment of duty and co taking delivery of cargo from customs after clearance along with documents.\n\n                                                    <!-- Some of the documents involved in customs clearance are:\n                                                    \n                                                    <li>\n                                                    Exports Documentation: Purchase order from Buyer, Sales Invoice, Packing List, Shipping bill, Bill of Lading or airway bill, Certificate of Origin and any other specific documentation as specified by the buyer, or as required by financial institutions or LC terms or as per importing country regulations.\n                                                    </li>\n                                                    \n                                                    <li>\n                                                    Imports Documentation: Purchase Order from Buyer, Sales Invoice of the supplier, Bill of Entry, Bill of Lading or Airway bill, Packing List, Certificate of Origin, and any other specific documentation required by the buyer, or financial institution or the importing country regulation.</span></p>\n                                                    </li>  -->",
"<p>Primegate embeds TRA-accredited clerks inside Dar, Tanga and Mtwara command posts. We pre-file entries, chase exemptions, brief inspectors and deliver HSSE sign-offs so your cargo is released with photographic evidence and zero guesswork.</p>"
)

replace(
"<p><span style=\"font-size: 20px; color: #fff;\">\n                                                   Prime Gate provides professional logistics services to clients across a number of industries within East Africa. Our solutions are cost effective, timely, and secure. We are a customer driven company that strive to deliver at international standards, providing local solutions.\n                                                </span><span class=gdlr-core-space-shortcode style=\"margin-top: 20px ;\"></span><span style=\"font-size: 18px;\">\n                                                    <li>Air Freight</li>\n                                                    <li>Sea Freight</li>\n                                                    <li>Road Freight</li>\n                                                </span></p>",
"<p><span style=\"font-size: 20px; color: #fff;\">From chartered freighters to bonded road convoys, Primegate synchronizes sea, air and inland legs with a single situational picture. Expect proactive dwell alerts, escort coordination and HSSE-ready drivers.</span><span class=gdlr-core-space-shortcode style=\"margin-top: 20px ;\"></span><span style=\"font-size: 18px;\">\n                                                    <li>Air, sea &amp; lake feeder capacity protection</li>\n                                                    <li>Control towers in Dar, Nairobi, Kampala &amp; Ndola</li>\n                                                    <li>Abnormal-load &amp; cold-chain specialists</li>\n                                                </span></p>"
)

replace(
"<p><span style=\"font-size: 20px; color: #fff;\">\n                                                    We can transport goods in pieces separately, rather than being shipped in a container, consolidated and shipped in crates, bags, boxes, drums, barrels without the use of containers\n                                                    </span></p>",
"<p><span style=\"font-size: 20px; color: #fff;\">Primegate engineers every abnormal move: route surveys, axle simulations, police escort scheduling, removable obstacle programs and remote site rigging. Mission dashboards keep EPC, OEM and financier stakeholders aligned minute-by-minute.</span></p>"
)

replace(
"<p><span style=\"font-size: 20px; color: #fff;\">\n                                                    Prime Gate operates three separate warehousing facilities strategically located in close proximity to the Dar-es-Salaam port and city center. our warehouses are ideal for general storage, specialized storage, deconsolidation of containers, and cross-docking. Our facilities have:\n                                                    <li>\n                                                        24/7 security, \n                                                    </li>\n                                                    <li>\n                                                        CCTV security cameras,\n                                                    </li>\n                                                    <li>\n                                                     and electric fencing. \n\n                                                    </li>\n                                                    <!-- Features -->\n                                                    <!-- ICMI approved facility\n                                                    GCLA certified for chemicals, including bunded area\n                                                    TFDA certified to store food items \n                                                    OSHA certified    \n                                                 -->\n                                                </span></p>",
"<p><span style=\"font-size: 20px; color: #fff;\">Three Primegate smart warehouses near Dar port provide bonded + ambient zones, barcode inventory, IoT temperature sensing and CCTV/armed response. Use them for deconsolidation, VAS kitting, duty-staged stock or rapid humanitarian pushes.</span></p>"
)

replace(
"<p>Prime Gate Enthusiasm and dedication toward providing excellent service to customers. Even if it involves going above and beyond to ensure that customers have a positive experience and are satisfied with the service they receive.\n                                               <span class=gdlr-core-space-shortcode style=\"margin-top: 25px ;\"></span>",
"<p>Every client is assigned a mission captain plus HSSE, finance and regulatory liaisons. We publish live situation boards, variance reports and visual evidence so stakeholders always know the next move.\n                                               <span class=gdlr-core-space-shortcode style=\"margin-top: 25px ;\"></span>"
)

replace(
"<p>Prime Gate works with ambitious goals, we consistently seek feedback regarding their performance.\n                                                    <!-- <span class=gdlr-core-space-shortcode style=\"margin-top: 25px ;\"></span> -->",
"<p>Performance KPIs cover dwell reduction, permit hit rates, HSSE hours and budget adherence. Lessons learned from each mission feed the next corridor, creating a compounding advantage.\n                                                    <!-- <span class=gdlr-core-space-shortcode style=\"margin-top: 25px ;\"></span> -->"
)

replace(
"<p> Prime Gate casts aside thoughts of failure and allowing our mind to concentrate on the strategic actions necessary to be successful in any task ahead.\n                                                         <span class=gdlr-core-space-shortcode style=\"margin-top: 25px ;\"></span>",
"<p>Primegates culture rewards transparent escalation and decisive recovery plans. Whether flooding, protests or inspection holds emerge, our teams activate contingency routes within hours.\n                                                         <span class=gdlr-core-space-shortcode style=\"margin-top: 25px ;\"></span>"
)

replace(
"Take a tour and see how the greatest Gateway to 8 countries in Africa works to cater to their Logistics needs.",
"Take a tour of Primegates mission center and see how we orchestrate customs, warehousing and inland convoys across East &amp; Central Africa."
)

path.write_text(text)
