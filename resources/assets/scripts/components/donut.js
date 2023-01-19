// https://gist.github.com/gre/1650294
/* eslint-disable no-mixed-operators */
const easeInOutCubic = t => (t < 0.5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1);

export default class Donut {
  constructor($donut) {
    this.$donut = $donut;
    this.$segment = $donut.find('.donut-segment');
    this.$value = $donut.find('.wp-block-carney-donut__value__number');
    this.activeIndex = 0;
    this.transitionDuration = 2000;
    this.transitionInterval = 100;
    this.values = [
      {
        value: parseFloat($donut.data('start-value')) || 0,
        color: $donut.data('start-color')
      },
      {
        value: parseFloat($donut.data('end-value')) || 0,
        color: $donut.data('end-color')
      }
    ];
  }

  getValue(index) {
    return this.values[index].value || this.values[index].value;
  }

  setActiveIndex(activeIndex = 0, withTransition = true) {
    this.activeIndex = activeIndex;
    const activeValue = this.getValue(activeIndex);
    this.$donut.toggleClass('wp-block-carney-donut--no-transition', !withTransition);
    this._transitionChartSegment(activeValue);
    this._transitionValueText(activeValue, withTransition);
    this._transitionChartColor();
  }

  _transitionChartSegment(activeValue) {
    this.$segment.attr('stroke-dasharray', `${activeValue} ${100 - activeValue}`);
  }

  _transitionChartColor() {
    if (this.values[1].color) {
      const activeColor = this.values[this.activeIndex].color;
      this.$segment.attr('stroke', `${activeColor}`);
    }
  }

  _transitionValueText(activeValue, withTransition = true) {
    if (this.valueTransitionInterval) {
      clearInterval(this.valueTransitionInterval);
    }

    if (!withTransition) {
      this.$value.text(activeValue);
      return;
    }

    const currentValue = parseFloat(this.$value.text());
    const startValue = this.getValue(this.activeIndex === 0 ? 1 : 0);
    const transitionRange = Math.abs(startValue - activeValue);
    const transitionDirection = activeValue > startValue ? 1 : -1;
    let transitionPct = Math.abs(startValue - currentValue) / transitionRange;
    let transitionTime = transitionPct * this.transitionDuration;
    this.valueTransitionInterval = setInterval(() => {
      transitionTime += this.transitionInterval;
      transitionPct = Math.min(Math.max(transitionTime / this.transitionDuration, 0), 1);
      const newValue = Math.round(
        startValue + transitionDirection * easeInOutCubic(transitionPct) * transitionRange
      );
      this.$value.text(newValue);

      if (transitionPct === 1) {
        clearInterval(this.valueTransitionInterval);
      }
    }, this.transitionInterval);
  }
}
