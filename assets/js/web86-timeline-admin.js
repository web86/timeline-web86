jQuery(document).ready(function ($) {
  var count = $(".repeatable-fieldset").length; // Get quantity of existing field groups

  $(".add-row").on("click", function () {
    count++;
    console.log("count: ", count);
    var row = $(".empty-row.screen-reader-text").clone(true);
    row.removeClass("empty-row screen-reader-text");
    row.addClass("repeatable-fieldset");
    row.find(".count").text(count);
    row
      .find('input[name="field1"]')
      .attr("name", "web86_field_name[0][" + count + "][field1]");
    row
      .find('input[name="field2"]')
      .attr("name", "web86_field_name[0][" + count + "][field2]");
    row.find(".remove-sub-row").on("click", function () {
      $(this).parent().remove();
      subcount--;
      row.find(".sub-repeatable-fieldset").each(function (index) {
        $(this)
          .find(".sub-count")
          .text(index + 1);
      });
      return false;
    });

    $(".wfieldset").append(row);
    return false;
  });

  $(".remove-row").on("click", function () {
    $(this).closest(".repeatable-fieldset").remove();
    count--; // reducing the counter when deleting a group of fields
    $(".repeatable-fieldset").each(function (index) {
      $(this)
        .find(".count")
        .text(index + 1); // updating the counter values for all field groups
    });
    return false;
  });

  // Adding a nested group of fields
  $(document).on("click", ".add-subfield-row", function (event) {
    event.preventDefault();
    var $parent_fieldset = $(this).closest(".repeatable-fieldset");
    
    var $subfields_container = $parent_fieldset.find(".subfields-container");
    var $subfield_template = $(
      ".repeatable-subfieldset.empty-subfieldset"
    ).clone();
    var subfield_index = $subfields_container.children().length;
    var field_index = $parent_fieldset.index() + 1;
    var new_html = $subfield_template
      .html()
      .replace(/mysubfield/g, "web86_field_name")
      .replace(/%group_index%/g, field_index)
      .replace(/%sub_group_index%/g, subfield_index);
    $subfields_container.append(new_html);
    $subfield_template.removeClass("empty-subfieldset screen-reader-text");
  });
  // Deleting a nested group of fields
  $(document).on("click", ".remove-sub-field-row", function (event) {
    event.preventDefault();
    $(this).closest(".subfield-container").remove();
  });
});
