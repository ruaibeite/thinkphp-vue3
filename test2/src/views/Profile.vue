<template>
    <el-tabs v-model="activeName" class="demo-tabs">
      <el-tab-pane label="User" name="first">
        <div v-if="activeName === 'first'">
         
        <Home/>
         
        </div>
      </el-tab-pane>
      <el-tab-pane label="Config" name="second">
        <div v-if="activeName === 'second'">
          
          <h3>Config Content</h3>
          
        </div>
      </el-tab-pane>
      <el-tab-pane label="Role" name="third">
        <div v-if="activeName === 'third'">
       
          <h3>Role Content</h3>
         
        </div>
      </el-tab-pane>
      <el-tab-pane label="Task" name="fourth">
        <div v-if="activeName === 'fourth'">
          
          <h3>Task Content</h3>
          
        </div>
      </el-tab-pane>
    </el-tabs>
  </template>
  
  <script lang="ts" setup>
  import Home from '../views/Home.vue'
  import { ref } from 'vue'
  import type { TabsPaneContext } from 'element-plus'
  import { ElTabs, ElTabPane } from 'element-plus';
  import 'element-plus/dist/index.css';
  
  const activeName = ref('first')
  
  const handleClick = (tab: TabsPaneContext, event: Event) => {
    console.log(tab, event)
  }
  </script>
  
  <style>
  .demo-tabs > .el-tabs__content {
    
    color: #6b778c;
    font-size: 32px;
    font-weight: 600;
  }
  </style>
  