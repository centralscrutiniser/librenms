<?php

use LibreNMS\RRD\RrdDefinition;

$oid_list = 'jnxJsSPUMonitoringCurrentFlowSession.0 jnxJsSPUMonitoringCPUUsage.0 jnxJsSPUMonitoringMemoryUsage.0';
$junos = snmp_get_multi($device, $oid_list, '-OUQs', 'JUNIPER-SRX5000-SPU-MONITORING-MIB');

if (is_numeric($junos[0]['jnxJsSPUMonitoringCurrentFlowSession'])) {
    $tags = array(
        'rrd_def' => RrdDefinition::make()->addDataset('spu_flow_sessions', 'GAUGE', 0),
    );
    $fields = array(
        'spu_flow_sessions' => $junos[0]['jnxJsSPUMonitoringCurrentFlowSession'],
    );

    data_update($device, 'junos_jsrx_spu_sessions', $tags, $fields);

    $graphs['junos_jsrx_spu_sessions'] = true;
    echo ' Flow Sessions';
}

if (is_numeric($junos[0]['jnxJsSPUMonitoringCPUUsage'])) {
    $tags = array(
        'rrd_def' => RrdDefinition::make()->addDataset('spu_cpu', 'GAUGE', 0),
    );
    $fields = array(
        'spu_cpu' => $junos[0]['jnxJsSPUMonitoringCPUUsage'],
    );

    data_update($device, 'junos_jsrx_spu_cpu', $tags, $fields);

    $graphs['junos_jsrx_spu_cpu'] = true;
    echo ' SPU CPU%';
}

if (is_numeric($junos[0]['jnxJsSPUMonitoringMemoryUsage'])) {
    $tags = array(
        'rrd_def' => RrdDefinition::make()->addDataset('spu_mem', 'GAUGE', 0),
    );
    $fields = array(
        'spu_mem' => $junos[0]['jnxJsSPUMonitoringMemoryUsage'],
    );

    data_update($device, 'junos_jsrx_spu_mem', $tags, $fields);

    $graphs['junos_jsrx_spu_mem'] = true;
    echo ' SPU MEM%';
}

$version = snmp_get($device, 'jnxVirtualChassisMemberSWVersion.0', '-Oqv', 'JUNIPER-VIRTUALCHASSIS-MIB');
if (empty($version)) {
    preg_match('/\[(.+)\]/', snmp_get($device, '.1.3.6.1.2.1.25.6.3.1.2.2', '-Oqv', 'HOST-RESOURCES-MIB'), $jun_ver);
    $version = $jun_ver[1];
}

if (strpos($device['sysDescr'], 'olive')) {
    $hardware = 'Olive';
    $serial   = '';
} else {
    $hardware = snmp_get($device, 'sysObjectID.0', '-Ovqs', '+Juniper-Products-MIB:JUNIPER-CHASSIS-DEFINES-MIB', 'junos');
    $hardware = 'Juniper '.rewrite_junos_hardware($hardware);
    $serial   = snmp_get($device, '.1.3.6.1.4.1.2636.3.1.3.0', '-OQv', '+JUNIPER-MIB', 'junos');
}

$features       = '';
