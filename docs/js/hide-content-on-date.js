/**
 * Hides HTML elements after their expiration date.
 *
 * Add `data_hide_me_on: "YYYY-MM-DD"` property to bannerimage or
 * presentationtext structure in any pages that will get converted to
 * `data_hide_me_on: "YYYY-MM-DD"` attribute of the respective html element
 * that should be hidden after the specified date.
 *
 * Example: In homepage you want to display the event image,
 * and it should be hidden after event date after 2026-09-26.
 *
 * Edit docs/_data/pages/homepage.yml file.
 *
 * Add bannerimage structure with data_hide_me_on: "2026-09-26" property
 *
 *   - structure: bannerimage
 *       img:
 *         en:
 *           image: /media/salon-affaires-municipales-2026-en.png
 *           alt: "Biothermica exhibitor at the Municipal Affairs Show 2026 - September 24-25 - Québec City Convention Centre"
 *         fr:
 *           image: /media/salon-affaires-municipales-2026.png
 *           alt: "Biothermica exposant au Salon Affaires Municipales 2026 - 24 et 25 septembre - Centre des congrès de Québec"
 *       link:
 *         en: https://fqm.ca/evenements/congres-fqm/le-salon-affaires-municipales/
 *         fr: https://fqm.ca/evenements/congres-fqm/le-salon-affaires-municipales/
 *       data_hide_me_on: "2026-09-26"
 *
 * when page generated then data-hide-me-on="2026-09-26" added to the img tag.
 *
 *  <img data-hide-me-on="2026-09-26" src="/media/biothermica-au-congres-genial-de-lAIMQ.jpeg" ...  />
 *
 * when the page this scripts gets all the data-hide-me-on attribute element in the page.
 * and hides those element.
 *
 * i.e.. The image element is hidden after "2026-09-26".
 *
 * Use this functionality to display the specific event image or content
 *  that has to be hidden when event date is passed.
 *
 * Right now data-hide-me-on functionality is added to bannerimage or presentationtext structure.
 *
 * you can edit any structure in docs/_includes/sections/ and inside the opening tag add
 *
 * {% if _block.data_hide_me_on %}
 *  data-hide-me-on="{{ _block.data_hide_me_on }}"
 * {% endif %}
 *
 * example :- you want to hide image then,
 *
 * <img
 * {% if _block.data_hide_me_on %}
 *  data-hide-me-on="{{ _block.data_hide_me_on }}"
 * {% endif %}
 * ...  />
 *
 */
document.querySelectorAll('[data-hide-me-on]').forEach((element) => {
  const expiryDate = element.dataset.hideMeOn;

  // Parse as a local date to avoid timezone issues
  const [year, month, day] = expiryDate.split('-').map(Number);
  const expiry = new Date(year, month - 1, day);

  // Today, with the time removed
  const today = new Date();
  today.setHours(0, 0, 0, 0);

  if (expiry < today) {
    element.style.display = 'none';
  }

});
