<template>
  <code class="whitespace-pre-line">
    <span
      v-for="(line, index) in output"
      :key="index"
      class="box-border flex gap-2 border-slate-500 px-1 hover:-my-[2px] hover:border-y-2"
      style="overflow-anchor: none"
    >
      <template v-if="isObject(line)">
        <span class="text-slate-400" :title="line.time.fromNow()">
          {{ line.time.format('HH:mm:ss') }}
        </span>

        <span :class="logLevelText(line.level)" class="grow">
          {{ line.message }}
        </span>

        <span class="text-xs text-slate-400/50">
          {{ line.caller }}
        </span>
      </template>

      <template v-else>
        {{ line }}
      </template>
    </span>
  </code>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { isObject } from 'lodash';
import moment, { Moment } from 'moment';

type LogLevel = 'DEBUG' | 'INFO' | 'WARN' | 'ERROR';

interface LogLine {
  time: Moment;
  caller: string;
  level: LogLevel;
  message: string;
}

export default defineComponent({
  components: {},

  props: {
    log: {
      type: String,
      required: true,
    },
  },

  data() {
    return {};
  },

  methods: {
    moment,

    isObject,

    transformLine(line: string): LogLine | string {
      const match =
        /^\[(\d{2}:\d{2}:\d{2})\] \[(.*)\/(DEBUG|INFO|WARN|ERROR|COMMAND|RESPONSE)\]: (.*)$/g.exec(
          line
        );
      if (!match) {
        return line;
      }

      const time = moment(match[1], 'HH:mm:ss');

      if (time.isAfter(moment().add(1, 'minute'))) {
        time.subtract(1, 'day');
      }

      const logLine: LogLine = {
        time,
        caller: match[2],
        level: match[3] as LogLevel,
        message: match[4],
      };

      return logLine;
    },

    logLevelText(level: LogLevel) {
      switch (level) {
        case 'DEBUG':
          return 'text-slate-600';
        case 'INFO':
          return 'text-slate-100';
        case 'WARN':
          return 'text-yellow-600';
        case 'ERROR':
          return 'text-red-500';
      }
    },
  },

  computed: {
    output() {
      const lines: string[] = this.log.split('\n');
      return lines.map(this.transformLine);
    },
  },
});
</script>
